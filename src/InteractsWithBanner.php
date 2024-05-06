<?php

namespace Laravel\Jetstream;

trait InteractsWithBanner
{
    /**
     * Update the banner message.
     *
     * @param  string  $message
     * @return void
     */
    protected function banner($message)
    {
        $this->dispatch('banner-message',
            style: 'success',
            message: $message,
        );
    }

    /**
     * Update the banner message with a warning message.
     *
     * @param  string  $message
     * @return void
     */
    protected function warningBanner($message)
    {
        $this->dispatch('banner-message',
            style: 'warning',
            message: $message,
        );
    }

    /**
     * Update the banner message with a danger / error message.
     *
     * @param  string  $message
     * @return void
     */
    protected function dangerBanner($message)
    {
        $this->dispatch('banner-message',
            style: 'danger',
            message: $message,
        );
    }
}
