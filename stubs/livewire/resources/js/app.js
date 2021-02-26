require('./bootstrap');

require('alpinejs');

window.ToggleDark = () => {
    return {
        darkMode: false,

        created() {
            this.darkMode = JSON.parse(localStorage.getItem('darkMode'))

            this.set()
        },

        toggle() {
            this.darkMode = !this.darkMode

            localStorage.setItem('darkMode', this.darkMode)

            this.set()
        },

        set() {
            if (this.darkMode) {
                document.querySelector('html').classList.add('dark')
                return
            }

            document.querySelector('html').classList.remove('dark')
        }
    }
}

