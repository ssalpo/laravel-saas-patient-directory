<template>
    <FormSelect
        ref="selectDoctors"
        v-bind="$attrs"
        searchable
        prefetch
        remote
        :remote-url="route('autocomplete.doctors')"
        full
        @selected="$emit('selected', $event)"
        placeholder="Выберите доктора"
    />
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectDoctors",
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectDoctors.refreshData()
        }
    }
}
</script>
