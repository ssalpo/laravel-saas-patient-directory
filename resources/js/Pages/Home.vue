<template>
    <Head>
        <title>Home Page</title>
    </Head>

    <h1>Home page</h1>

    <Link :href="route('products.create')">
        добавить продукт
    </Link>

</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Head, Link}
}
</script>
