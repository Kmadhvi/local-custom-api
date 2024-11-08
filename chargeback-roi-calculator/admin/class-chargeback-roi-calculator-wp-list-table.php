<?php if (!defined("ABSPATH")) {
    die();
} // If this file is called directly, abort.

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}
/**
 * Define a custom WP List Table class
 *
 * @since      1.0.0
 * @package    Chargeback_Roi_Calculator
 * @author     World Web Technology <biz@worldwebtechnology.com>
 *
 */

class Chargeback_Roi_Calculator_Wp_List_Table extends WP_List_Table
{
    protected $table_data;

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct()
    {
        parent::__construct([
            "singular" => "chargeback_roi_calculator_wp_list_table", //Singular label
            "plural" => "chargeback_roi_calculator_wp_list_table", //plural label, also this well be one of the table css class
            "ajax" => false, //We won't support Ajax for this table
        ]);
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns()
    {
        return $columns = [
            "cb" => '<input type="checkbox" />',
            "croic_id" => __("ID"),
            "croic_industry" => __("Industry"),
            "croic_psp" => __("PSP"),
            "croic_reason_group" => __("Reason Group"),
            "croic_recovery_rate" => __("Recovery rate %"),
        ];
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */

    public function get_sortable_columns()
    {
        return $sortable = [
            "croic_id" => ["croic_id", false],
            "croic_industry" => ["croic_industry", false],
            "croic_psp" => ["croic_psp", false],
            "croic_reason_group" => ["croic_reason_group", false],
            "croic_recovery_rate" => ["croic_recovery_rate", false],
        ];
    }

    /* difines the bulk action */
    function get_bulk_actions()
    {
        $actions = [
            "delete_all" => __("Delete All"),
        ];
        return $actions;
    }

    /* defines to get the table data from DB */

    private function get_table_data()
    {
        global $wpdb;

        $table = $wpdb->prefix . "roi_industry_psp";

        $where_clause = "";

        if (!empty($_REQUEST["croic_industry_filter"])) {
            $where_clause .=
                ' AND croic_industry = "' .
                $_REQUEST["croic_industry_filter"] .
                '"';
        }

        if (!empty($_REQUEST["croic_psp_filter"])) {
            $where_clause .=
                ' AND croic_psp = "' . $_REQUEST["croic_psp_filter"] . '"';
        }

        if (!empty($_REQUEST["s"])) {
            $where_clause .=
                ' AND ( croic_industry LIKE "%' .
                $_REQUEST["s"] .
                '%" || croic_psp LIKE "%' .
                $_REQUEST["s"] .
                '%")';
        }

        //echo "SELECT * from {$table} WHERE 1 ".$where_clause." order by `croic_id` ASC";

        return $wpdb->get_results(
            "SELECT * from {$table} WHERE 1 " .
                $where_clause .
                " order by `croic_id` ASC",
            ARRAY_A
        );
    }

    /* defines to sort the data */

    function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = !empty($_GET["orderby"]) ? $_GET["orderby"] : "croic_id";

        // If no order, default to asc
        $order = !empty($_GET["order"]) ? $_GET["order"] : "asc";

        // Determine sort order
        $result = strnatcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return $order === "asc" ? $result : -$result;
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */

    function prepare_items()
    {
        $this->process_bulk_action();

        $this->table_data = $this->get_table_data();

        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $primary = "croic_id";

        $this->_column_headers = [$columns, $hidden, $sortable, $primary];

        usort($this->table_data, [&$this, "usort_reorder"]);

        $per_page = 10;
        $current_page = $this->get_pagenum();

        $total_items = count($this->table_data);

        $this->table_data = array_slice(
            $this->table_data,
            ($current_page - 1) * $per_page,
            $per_page
        );

        $this->set_pagination_args([
            "total_items" => $total_items,
            "per_page" => $per_page,
            "total_pages" => ceil($total_items / $per_page),
        ]);

        $this->items = $this->table_data;
    }

    /* defines default columns */
    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case "croic_id":
            case "croic_industry":
            case "croic_psp":
            case "croic_reason_group":
            case "croic_recovery_rate":
            default:
                return $item[$column_name];
        }
    }

    /* defines bulk checkbox input field */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="element[]" value="%s" />',
            $item["croic_id"]
        );
    }

    /* defines to prcess bulk action triggered */

    public function process_bulk_action()
    {
        global $wpdb;

        $table = $wpdb->prefix . "roi_industry_psp";

        $action = $this->current_action();

        if ("delete_all" === $action) {
            $delete_ids = isset($_REQUEST["element"])
                ? esc_sql($_REQUEST["element"])
                : "";

            if (!empty($delete_ids)) {
                foreach ($delete_ids as $did) {
                    $wpdb->query(
                        "DELETE FROM $table WHERE croic_id='" . $did . "'"
                    );
                }
            }
        }
    }

    /* defines filter dropdowns */

    function extra_tablenav($which)
    {
        global $wpdb, $testiURL, $tablename, $tablet;
        $move_on_url = "&cat-filter=";
        if ($which == "top") { ?>
        <div class="alignleft actions bulkactions">

            <?php
            global $wpdb;
            $table = $wpdb->prefix . "roi_industry_psp";

            $croic_industry = $wpdb->get_results(
                "SELECT DISTINCT `croic_industry` FROM $table ",
                ARRAY_A
            );

            $croic_psp = $wpdb->get_results(
                "SELECT DISTINCT `croic_psp` FROM $table",
                ARRAY_A
            );
            ?>            
        
             <select id="croic_industry_filter" name="croic_industry_filter" class="ind-psp-filter-dropdown">
                    <option value=""><?php esc_html_e(
                        "--- Select Industry ---",
                        "chargeback-roi-calculator"
                    ); ?> </option>
                    <?php foreach ($croic_industry as $key => $value) {
                        $croic_industry_selected = "";

                        if (
                            isset($_REQUEST["croic_industry_filter"]) &&
                            $_REQUEST["croic_industry_filter"] != "" &&
                            $value["croic_industry"] ==
                                $_REQUEST["croic_industry_filter"]
                        ) {
                            $croic_industry_selected = "selected=selected";
                        }

                        echo '<option value="' .
                            $value["croic_industry"] .
                            '"  ' .
                            $croic_industry_selected .
                            ">" .
                            $value["croic_industry"] .
                            "</option>";
                    } ?>                                        
              </select>

              <select id="croic_psp_filter" name="croic_psp_filter" class="ind-psp-filter-dropdown" > 
                    <option value=""><?php esc_html_e(
                        "--- Select PSP ---",
                        "chargeback-roi-calculator"
                    ); ?> </option>
                    <?php foreach ($croic_psp as $key => $value) {
                        $croic_psp_selected = "";

                        if (
                            isset($_REQUEST["croic_psp_filter"]) &&
                            $_REQUEST["croic_psp_filter"] != "" &&
                            $value["croic_psp"] == $_REQUEST["croic_psp_filter"]
                        ) {
                            $croic_psp_selected = "selected=selected";
                        }

                        echo '<option value="' .
                            $value["croic_psp"] .
                            '" ' .
                            $croic_psp_selected .
                            ">" .
                            $value["croic_psp"] .
                            "</option>";
                    } ?>
              </select>

            <input type="submit" value="Filter" class="button btn" />

            <?php  ?>  
        </div>
        <?php }
    }

    /* defines sngle row action like delete */

    public function column_croic_id($item)
    {
        $delete_link =
            get_admin_url() .
            "admin.php?page=industry_psp_listing&action=delete&croic_id=" .
            $item["croic_id"];

        $output = "";

        $output .= esc_html($item["croic_id"]);

        $actions = [
            "delete" =>
                '<a href="' .
                esc_url($delete_link) .
                '">' .
                esc_html__("Delete", "chargeback-roi-calculator") .
                "</a>",
        ];

        $row_actions = [];

        foreach ($actions as $action => $link) {
            $row_actions[] =
                '<span class="' . esc_attr($action) . '">' . $link . "</span>";
        }

        $output .=
            '<div class="row-actions">' .
            implode(" | ", $row_actions) .
            "</div>";

        return $output;
    }
}

$action = get_admin_url() . "admin.php?page=industry_psp_listing";
echo '<form method="get" name="frm_search_post" action="' . $action . '" >';
$wp_list_table = new Chargeback_Roi_Calculator_Wp_List_Table();
$wp_list_table->prepare_items();
$wp_list_table->search_box("Search", "search");
$wp_list_table->display();
?>
<input type="hidden" name="page" value="<?php echo $_REQUEST["page"]; ?>" />
<?php echo "</form>";
