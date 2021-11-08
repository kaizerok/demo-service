<?php

Route::match(['get', 'post'], 'webhook/paymentwall', 'WebhookController@paymentwall')->name('webhook.paymentwall');

//************ AR ************************
Route::get('entity-rule/payment-entities', 'EntityRuleController@getEntitiesByTransactionReference')->name('entity-rule.entities');
