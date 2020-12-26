<?php
/**
 * Error pages translation
 */
return [

    'message-send-error-yourself' => 'Send yourself a message that is not allowed.',
    'message-send-error-recipient' => 'The recipient does not exist.',
    'message-send-error-listing' => 'The listing does not exist.',
    'message-send-error-not-match' => 'Recipient and listing owner does not match.',
    'message-send' => 'The message sent successfully.',
    'message-view-error-own' => 'You can only view your thread.',
    'message-reply-error-own' => 'You can only reply to your thread.',

    'category-created' => 'Category created successfully.',
    'category-updated' => 'Category updated successfully.',
    'category-delete-error-custom-field' => 'You cannot delete category because it has custom field records. Please remove all its associate custom field records before deleting this category.',
    'category-delete-error-listing' => 'You cannot delete category because it has listings records. Please remove all its associate listings records before deleting this category.',
    'category-deleted' => 'Category deleted successfully.',

    'city-created' => 'The city created successfully.',
    'city-updated' => 'The city updated successfully.',
    'state-country-not-found' => 'State & country not found.',
    'city-delete-error-listing' => 'You cannot delete the city because it has listings records. Please remove all its associate listings records before deleting this city.',
    'city-deleted' => 'The city deleted successfully.',

    'comment-approved' => 'Comment approved successfully.',
    'comment-disapproved' => 'Comment disapproved successfully.',
    'comment-deleted' => 'Comment deleted successfully.',

    'country-created' => 'The country created successfully.',
    'country-updated' => 'The country updated successfully.',
    'country-delete-error-setting' => 'You cannot delete the country because of the website settings currently using this country.',
    'country-delete-error-listing' => 'You cannot delete the country because it has listings records. Please remove all its associate listings records before deleting this country.',
    'country-delete-error-state' => 'You cannot delete the country because it has state records. Please remove all its associate states before deleting this country.',
    'country-deleted' => 'The country deleted successfully.',

    'custom-field-created' => 'The custom field created successfully.',
    'custom-field-updated' => 'The custom field updated successfully.',
    'custom-field-delete-error-listing' => 'You cannot delete the custom field because one or more listings are using it. Please remove all its associate listings before deleting this custom field.',
    'custom-field-deleted' => 'The custom field deleted successfully.',

    'faq-created' => 'Faq created successfully.',
    'faq-updated' => 'Faq updated successfully.',
    'faq-deleted' => 'Faq deleted successfully.',

    'item-created' => 'Business listing created successfully.',
    'item-created-error-quota' => 'You have reached max featured business listing quota.',
    'item-created-error-paid' => 'You must buy a subscription to feature a business listing.',
    'item-updated' => 'Business listing updated successfully.',
    'item-deleted' => 'Business listing deleted successfully.',
    'item-approved' => 'Business listing approved successfully.',
    'item-disapproved' => 'Business listing dis-approved successfully.',
    'item-suspended' => 'Business listing suspended successfully.',

    'plan-created' => 'The plan created successfully.',
    'plan-updated' => 'The plan updated successfully.',
    'plan-delete-error-user' => 'You cannot delete the plan because it has been using by one or more users.',
    'plan-deleted' => 'The plan deleted successfully.',

    'setting-general-updated' => 'The general setting updated successfully.',
    'setting-about-updated' => 'The about page updated successfully.',
    'setting-terms-updated' => 'The terms of service page updated successfully.',
    'setting-privacy-updated' => 'The privacy policy page updated successfully.',

    'social-media-created' => 'Social media created successfully.',
    'social-media-updated' => 'Social media updated successfully.',
    'social-media-deleted' => 'Social media deleted successfully.',

    'state-created' => 'The state created successfully.',
    'state-updated' => 'The state updated successfully.',
    'state-delete-error-city' => 'You cannot delete the state because it has city records. Please remove all its associate cities before deleting this state.',
    'state-delete-error-listing' => 'You cannot delete the state because it has business listings records. Please remove all its associate business listings before deleting this state.',
    'state-deleted' => 'The state deleted successfully.',

    'subscription-updated' => 'The subscription updated successfully.',

    'testimonial-created' => 'Testimonial created successfully.',
    'testimonial-updated' => 'Testimonial updated successfully.',
    'testimonial-deleted' => 'Testimonial deleted successfully.',

    'user-created' => 'The user created successfully.',
    'user-updated' => 'The user updated successfully.',
    'user-password-changed' => 'The user password changed successfully.',
    'user-profile-updated' => 'The profile updated successfully.',
    'user-profile-password-changed' => 'The profile password changed successfully.',
    'user-suspended' => 'The user suspended successfully.',
    'user-unlocked' => 'User suspension removed successfully.',
    'user-deleted' => 'User account deleted successfully.',

    'paypal-plan-subscription-error' => 'Plan or subscription error.',
    'paypal-free-plan-upgrade' => 'Only support free plan upgrade. You must cancel the current paid plan before switching to others.',
    'paypal-wrong' => 'Something went wrong with PayPal.',
    'paypal-process-payemnt-error' => 'Error processing PayPal payment.',
    'paypal-order-paid' => 'Order :invoice_num has paid successfully.',
    'paypal-process-payemnt-error-order' => 'Error processing PayPal payment for Order :invoice_num.',
    'paypal-payment-canceled' => 'The payment has canceled.',
    'paypal-subscription-canceled' => 'Subscription canceled successfully.',
    'paypal-subscription-user-not-match' => 'Subscription and user do not match.',
    'paypal-subscription-not-found' => 'I cannot found a subscription.',

    'subscription-choose-plan' => 'Please choose a plan to continue.',
    'plan-not-exist' => 'The plan does not exist.',
    'plan-switched' => 'The plan switched successfully.',

    'demo-mode-prohibited' => 'Modification requests prohibited in demo mode.',
];
