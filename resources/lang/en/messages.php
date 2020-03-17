<?php

return [
    'error' => [
        'forbidden' => 'Access denied',
        'unavailable' => 'Oops, it looks like something went wrong',
        'unauthorized' => 'Unauthorized',
        'notfound' => 'Not found',
    ],

    'success' => [
        'created' => 'Successfully created',
        'updated' => 'Successfully updated',
        'removed' => 'Successfully removed',
    ],

    'users' => [
        'cant_delete_yourself' => 'Unable to remove the logged in user',
        'invalid_token' => 'Invalid token',
    ],

    'emails' => [
        'forgot_password' => [
            'subject' => 'Forgot Password',
            'action' => 'Reset Password',
            'content' => 'You are receiving this email because we received a password reset request for your account. If you did not request a password reset, no further action is required.',
        ],
    ],
];
