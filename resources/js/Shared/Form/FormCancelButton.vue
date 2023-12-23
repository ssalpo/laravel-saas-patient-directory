<script>
import {defineComponent} from 'vue'
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "FormCancelButton",
    components: {Link},
    props: {
        url: String,
        isProcessing: Boolean,
        btnLabel: {
            type: String,
            default: 'Отменить'
        }
    }
})
</script>

<template>
    <Link
        :href="url"
        :class="{disabled: isProcessing}"
        class="btn btn-default ml-2">
        {{ btnLabel}}
    </Link>
</template>
