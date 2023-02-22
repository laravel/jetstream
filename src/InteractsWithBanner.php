<?php

namespace Laravel\Jetstream;

trait InteractsWithBanner
{
    /**
     * Update the banner message.
     */
    protected function banner(string $message): void
    {
        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Update the banner message with a danger / error message.
     */
    protected function dangerBanner(string $message): void
    {
        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'danger',
            'message' => $message,
        ]);
    }
}
