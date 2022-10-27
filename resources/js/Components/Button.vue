<template>
    <button :type="type" @click="$emit('pressed')" :class="cssClass" :disabled="disabled"><slot></slot></button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'button',
        },
        look: {
            type: String,
            default: 'primary'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        tailwindStyle: {
            type: String,
            default: ''
        }
    },
    computed: {
        cssClass: function () {
            if (this.disabled) {
                return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-300 border border-transparent rounded-md ' + this.tailwindStyle
            } else {
                switch (this.look) {
                    case 'primary':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-pink-800 border border-transparent rounded-md hover:bg-pink-700 active:bg-pink-900 focus:outline-none focus:border-pink-900 focus:shadow-outline-indigo ' + this.tailwindStyle
                
                    case 'secondary':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-teal-800 border border-transparent rounded-md hover:bg-teal-700 active:bg-teal-900 focus:outline-none focus:border-teal-900 focus:shadow-outline-teal ' + this.tailwindStyle
                
                    case 'success':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-green-800 border border-transparent rounded-md hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:shadow-outline-green ' + this.tailwindStyle
                
                    case 'error':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-red-800 border border-transparent rounded-md hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-red ' + this.tailwindStyle
                
                    case 'warning':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-yellow-800 border border-transparent rounded-md hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:shadow-outline-yellow ' + this.tailwindStyle
                
                    case 'info':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-800 border border-transparent rounded-md hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue ' + this.tailwindStyle

                    case 'default':
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-300 border border-transparent rounded-md ' + this.tailwindStyle
                
                    default:
                        return 'inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray ' + this.tailwindStyle
                }
            }
        }
    }
}
</script>