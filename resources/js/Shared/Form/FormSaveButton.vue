<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "FormSaveButton",
    props: {
        isProcessing: Boolean,
        processingBtnLabel: {
            type: String,
            default: 'Сохранение...'
        },
        isEditing: Boolean,
        addBtnLabel: {
            type: String,
            default: 'Добавить'
        },
        editBtnLabel: {
            type: String,
            default: 'Сохранить'
        },
    }
})
</script>

<template>
    <button type="submit" :disabled="isProcessing" class="btn btn-primary">
        <span v-if="isProcessing">
            <i class="fas fa-spinner fa-spin"></i> {{processingBtnLabel}}
        </span>

        <span v-else>{{ isEditing ? editBtnLabel : addBtnLabel }}</span>
    </button>
</template>
