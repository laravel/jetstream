<?php

namespace Illuminate\Http
{
    class RedirectResponse
    {
        public function banner($message): RedirectResponse {}

        public function dangerBanner($message): RedirectResponse {}
    }
}
