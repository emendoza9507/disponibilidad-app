<?php

namespace App\Helpers;

trait FlashMessage
{
    protected static function message($level = 'info', $message = null)
    {
        if (session()->has('messages')) {
            $messages = session()->pull('messages');
        }

        $messages[] = $message = ['level' => $level, 'message' => $message];

        session()->flash('messages', $messages);

        return $message;
    }

    protected static function messages()
    {
        return self::hasMessages() ? session()->pull('messages') : [];
    }

    private static function hasMessages(): bool
    {
        return session()->has('messages');
    }

    public static function success($message): array
    {
        return self::message('success', $message);
    }

    public static function info($message): array
    {
        return self::message('info', $message);
    }

    public static function warning($message): array
    {
        return self::message('warning', $message);
    }

    public static function danger($message): array
    {
        return self::message('danger', $message);
    }
}
