<template>
    <div class="form-group">
        <label :class="{'form-asterisk': required}" v-show="label">{{ label }}</label>
        <FormSelect
            ref="selectUsers"
            v-bind="$attrs"
            searchable
            prefetch
            remote
            :remote-url="route('autocomplete.users')"
            full
            :invalid-text="validationError"
            placeholder="Выберите роль"
        />
    </div>
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectUsers",
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
    },
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectUsers.refreshData()
        }
    }
}
</script>
