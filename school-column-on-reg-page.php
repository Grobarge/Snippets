<?php
function tw_custom_columns($columns, $screen)
{
    // This is for the 'default' registration list table
    // Event Espresso -> Registrations.
    if ($screen == 'espresso_registrations_default') {
        // EEH_Array::insert_into_array() allows you to specific a specific key
        // that you want to add your additional column before/after.
        // This adds the custom-reg-qst-column just before 'actions' column.      
        $columns = EEH_Array::insert_into_array(
            $columns,
            array('custom-reg-qst-column' => 'School'),
            'actions'
        );
    }
    //Add a custom-reg-qst-column column to the contact list table.
    if ($screen == 'espresso_registrations_event_registrations') {
        //This is another method you can use that just adds the column to the end of the array.
        $columns['custom-reg-qst-column'] = 'Custom Reg Question';
    }
    return $columns;
}
add_filter('FHEE_manage_event-espresso_page_espresso_registrations_columns', 'tw_custom_columns', 10, 2);
function tw_checkin_table_custom_question($item, $screen)
{
    //Sanity check to confirm we have an EE_Registration object.
    if ($item instanceof EE_Registration) {
        //Pull the answer object for Question ID 11 using the current registration.
        $question_id = 11;
        $answer_obj = EEM_Answer::instance()->get_registration_question_answer_object($item, $question_id);
        if ($answer_obj instanceof EE_Answer) {
            //If we have an answer object, echo the 'pretty' value for it.
            echo $answer_obj->pretty_value();
        }
    }
}
// 'custom-reg-qst-column' in the action name here needs to be changed to match your column name set in the function above.
add_action('AHEE__EE_Admin_List_Table__column_custom-reg-qst-column__event-espresso_page_espresso_registrations', 'tw_checkin_table_custom_question', 10, 2);
//Enable sorting for the custom column.
function tw_ee_sortable_columns($sortable_columns, $screen)
{
    if ($screen == 'espresso_registrations_default') {
        $sortable_columns['custom-reg-qst-column'] = array('custom-reg-qst-column' => false);
    }
    return $sortable_columns;
}
add_filter('FHEE_manage_event-espresso_page_espresso_registrations_sortable_columns', 'tw_ee_sortable_columns', 10, 2);
// The models only accept certain values for orderby, normally the column name is used but if thats custom we'll need to pass a value
// the models understand. THis sets the order by to 'Answer.ANS_value' when you select the custom column above.
function tw_ee_get_orderby_for_registrations_query($order_by, $request)
{
    if (isset($order_by['custom-reg-qst-column'])) {
        $fixed_order_by = array(
            'Answer.ANS_value' => $order_by['custom-reg-qst-column']
        );
        unset($order_by['custom-reg-qst-column']);
        foreach ($order_by as $key => $value) {
            $fixed_order_by[$key] = $value;
        }
        return $fixed_order_by;
    }
    //Return the original order_by array.
    return $order_by;
}
add_filter('FHEE__Registrations_Admin_Page___get_orderby_for_registrations_query', 'tw_ee_get_orderby_for_registrations_query', 10, 2);
