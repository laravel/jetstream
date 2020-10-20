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
        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Update the banner message with an danger / error message.
     *
     * @param  string  $message
     * @return void
     */
    protected function dangerBanner($message)
    {
        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'danger',
            'message' => $message,
        ]);
    }
}
