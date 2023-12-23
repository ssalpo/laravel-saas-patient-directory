<template>
    <FormSelect
        ref="selectRoles"
        v-bind="$attrs"
        searchable
        prefetch
        remote
        :remote-url="route('autocomplete.roles')"
        full
        placeholder="Выберите роль"
    />
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectRoles",
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectRoles.refreshData()
        }
    }
}
</script>
