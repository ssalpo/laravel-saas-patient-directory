<script>
import {defineComponent} from 'vue'

export default defineComponent({
    props: {
        label: {
            type: String,
            required: false
        },
        required: {
            type: Boolean,
            default: false
        },
        validationError: String,
        modelValue: String
    }
})
</script>

<template>
    <div class="form-group">
        <label :class="{'form-asterisk': required}" v-show="label">{{ label }}</label>
        <input type="text" class="form-control"
               :class="{'is-invalid': validationError}"
               :value="modelValue"
               v-bind="$attrs"
               @input="$emit('update:modelValue', $event.target.value)">

        <div v-if="validationError" class="error invalid-feedback">
            {{ validationError }}
        </div>
    </div>
</template>

