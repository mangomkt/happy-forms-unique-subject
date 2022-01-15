<?php
/*
Plugin Name: Happy Forms Unique Subject Email Notification
Plugin URI: https://github.com/mangomkt/happy-forms-unique-subject
Description: Manage Custom Built Modules
Version: 0.01
Author: Curtis Grant

*/

add_filter( 'happyforms_email_alert', function( $email_message ) {
    $reply_to = $email_message->get_reply_to();

    if ( is_array( $reply_to ) && ! empty( $reply_to ) ) {
        $email_message->set_from( $reply_to[0] );
    }

    $subject = $email_message->get_subject();
    $form_id = $email_message->message['form_id'];
    $form = happyforms_get_form_controller()->get( $form_id );
    $parts = wp_list_pluck( $form['parts'], 'id', 'label' );
    $message_parts = $email_message->message['parts'];

    $name_label = 'First Name';
    $name_value = '';

    $email_label = 'Email Address';
    $email_value = '';

    if ( isset( $parts[$name_label] ) ) {
        $name_value = $message_parts[$parts[$name_label]];
        $email_value = $message_parts[$parts[$email_label]];
        $subject .= ' — ' . $name_value . '(' . $email_value . ')';

        $email_message->set_from_name( $name_value );
    }

    $email_message->set_subject( $subject );

    return $email_message;
} );

?>