<script>
import {defineComponent} from 'vue'
import autosize from "autosize/dist/autosize";
import Uuid from "../../Mixins/Uuid";

export default defineComponent({
    mixins:[Uuid],
    props: {
        label: {
            type: String,
            required: false
        },
        required: {
            type: Boolean,
            default: false
        },
        autoresize: Boolean,
        validationError: String,
        modelValue: String
    },
    mounted() {
        if(this.autoresize) {
            autosize(this.$refs[this.uid])
        }
    }
})
</script>

<template>
    <div class="form-group">
        <label :class="{'form-asterisk': required}" v-show="label">{{ label }}</label>

        <textarea :class="{'is-invalid': validationError}"
                  :ref="uid"
                  :value="modelValue"
                  v-bind="$attrs"
                  class="form-control"
                  @input="$emit('update:modelValue', $event.target.value)"
        ></textarea>

        <div v-if="validationError" class="error invalid-feedback">
            {{ validationError }}
        </div>
    </div>
</template>

