<?php

function createResponse($success, $message, $data = null)
{

    if ($data === null) {
        return [
            'success' => $success,
            'message' => $message
        ];
    } else {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];
    }
}
