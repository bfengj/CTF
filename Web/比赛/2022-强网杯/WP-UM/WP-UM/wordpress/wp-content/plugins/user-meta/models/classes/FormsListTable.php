<?php
namespace UserMeta;

/**
 *
 * @since 1.1.7
 */
class FormsListTable extends \WP_List_Table
{

    function __construct()
    {
        parent::__construct([
            'singular' => __('Form', 'user-meta'),
            'plural' => __('Forms', 'user-meta'),
            'ajax' => false
        ]);
    }

    function column_name($item)
    {
        $deleteUrl = sprintf('?page=user-meta&form=%s&action=delete&_wpnonce=%s', urlencode($item['form_key']), (new RouteResponse())->createNonce('delete'));
        $deleteConfirmation = sprintf("You are about to delete this form \'%s\'. Click \'Cancel\' to stop, \'OK\' to delete.", esc_html($item['form_key']));
        $deleteLink = sprintf('<a href="%s" onclick="if(confirm(\'%s\')){return true;}return false;">' . __('Delete', 'user-meta') . '</a>', $deleteUrl, $deleteConfirmation);

        /**
         * Build row actions
         */
        $actions = [
            'edit' => sprintf('<a href="?page=user-meta&form=%s&action=edit">' . __('Edit', 'user-meta') . '</a>', urlencode($item['form_key'])),
            'duplicate' => sprintf('<a href="?page=user-meta&form=%s&action=duplicate&_wpnonce=%s">' . __('Duplicate', 'user-meta') . '</a>', urlencode($item['form_key']), (new RouteResponse())->createNonce('duplicate')),
            //'delete' => sprintf('<a href="?page=user-meta&form=%s&action=delete&_wpnonce=%s" onclick="if(confirm(\'%s\')){return true;}return false;">' . __('Delete', 'user-meta') . '</a>', urlencode($item['form_key']), (new RouteResponse())->createNonce('delete'), "You are about to delete this form \'{$item['form_key']}\'.  \'Cancel\' to stop, \'OK\' to delete.")
            'delete' => $deleteLink
        ];

        // Return the title contents
        return sprintf('%1$s %2$s', esc_html($item['form_key']), $this->row_actions($actions));
    }

    function column_shortcode($item)
    {
        $html = '';
        $html .= sprintf('<p><strong>%1$s:</strong> [user-meta-profile form="%2$s"]</p>', __('Profile', 'user-meta'), esc_html($item['form_key']));
        $html .= sprintf('<p><strong>%1$s:</strong> [user-meta-registration form="%2$s"]</p>', __('Registration', 'user-meta'), esc_html($item['form_key']));

        return $html;
    }

    /**
     * ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed.
     * It ALWAYS needs to
     * have it's own method.
     *
     * @see \WP_List_Table::::single_row_columns()
     * @param array $item
     *            A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     *         ************************************************************************
     */
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'], // Let's simply repurpose the table's singular label ("movie")
        /* $2%s */
        esc_attr($item['form_key'])); // The value of the checkbox should be the record's id
    }

    function column_default($item, $column_name)
    {
        return print_r($item, true);
    }

    /**
     * ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles.
     * This should
     * return an array where the key is the column slug (and class) and the value
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     *
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     *
     * @see \WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     *         ************************************************************************
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', // Render a checkbox instead of text
            'name' => __('Form Name', 'user-meta'),
            'shortcode' => __('Shortcode', 'user-meta')
        );
        return $columns;
    }

    /**
     * ************************************************************************
     * Optional.
     * If you want one or more columns to be sortable (ASC/DESC toggle),
     * you will need to register it here. This should return an array where the
     * key is the column that needs to be sortable, and the value is db column to
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     *
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     *
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     *         ************************************************************************
     */
    function get_sortable_columns()
    {
        $sortable_columns = [
            'name' => [
                'name',
                false
            ]
        ]; // true means it's already sorted

        return $sortable_columns;
    }

    /**
     * ************************************************************************
     * Optional.
     * If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     *
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     *
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     *
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     *         ************************************************************************
     */
    function get_bulk_actions__()
    {
        $actions = [
            'delete' => 'Delete'
        ];

        return $actions;
    }

    /**
     * ************************************************************************
     * Optional.
     * You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     *
     * @see $this->prepare_items() ************************************************************************
     */
    function process_bulk_action()
    {

        // Detect when a bulk action is being triggered...
        if ('duplicate' === $this->current_action())
            $this->duplicate_single_form();

        elseif ('delete' === $this->current_action())
            $this->delete_single_form();
    }

    private function duplicate_single_form()
    {
        (new RouteResponse())->verifyAdminNonce();

        global $userMeta;
        if (empty($_REQUEST['form']))
            return;

        $formName = esc_attr($_REQUEST['form']);
        $forms = $userMeta->getData('forms');

        if (isset($forms[$formName])) {
            $newFormName = $formName . '-duplicated';
            $newFormData = $forms[$formName];
            $newFormData['form_key'] = $newFormName;
            $forms[$newFormName] = $newFormData;
            $userMeta->updateData('forms', $forms);

            $msg = sprintf(__('Form "%s" is duplicated to "%s".', 'user-meta'), $formName, $newFormName);
            echo '<div id="message" class="updated below-h2"><p>' . $msg . '</p></div>';
        }
    }

    private function delete_single_form()
    {
        (new RouteResponse())->verifyAdminNonce();

        global $userMeta;
        if (empty($_REQUEST['form']))
            return;

        $formName = esc_attr($_REQUEST['form']);
        $forms = $userMeta->getData('forms');
        if (isset($forms[$formName])) {
            unset($forms[$formName]);
            $userMeta->updateData('forms', $forms);
            $msg = sprintf(__('Form "%s" is deleted.', 'user-meta'), $formName);
            echo '<div id="message" class="updated below-h2"><p>' . $msg . '</p></div>';
        }
    }

    /**
     * ************************************************************************
     * REQUIRED! This is where you prepare your data for display.
     * This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     *
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     *       ************************************************************************
     */
    function prepare_items()
    {
        global $userMeta;

        $per_page = 20;

        /**
         * REQUIRED.
         * Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        /**
         * REQUIRED.
         * Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );

        /**
         * Optional.
         * You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();

        $data = $userMeta->getData('forms');

        /*
         * function usort_reorder( $a, $b ) {
         * $orderby = ! empty( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : 'form_key';
         * $order = ! empty( $_REQUEST['order'] ) ? $_REQUEST['order'] : 'asc';
         * $result = strcmp( $a[ $orderby ], $b[ $orderby ] ); //Determine sort order
         * return ( $order === 'asc') ? $result : -$result; //Send final sort direction to usort
         * }
         * usort( $data, 'usort_reorder' );
         */

        if (isset($_REQUEST['orderby']) && $_REQUEST['order']) {
            if ('name' == $_REQUEST['orderby']) {
                if ('asc' == $_REQUEST['order'])
                    ksort($data);
                else
                    krsort($data);
            }
        }

        /**
         * REQUIRED for pagination.
         * Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination.
         * Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */

        // condition added to remove warning!
        // total item set to zero if no data found
        $total_items = ! empty($data) ? count($data) : 0;

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page.
         * We can use
         * array_slice() to
         */
        if (! empty($data) && is_array($data))
            $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        /**
         * REQUIRED.
         * Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $data;

        /**
         * REQUIRED.
         * We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items, // WE have to calculate the total number of items
            'per_page' => $per_page, // WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)
        )); // WE have to calculate the total number of pages
    }
}