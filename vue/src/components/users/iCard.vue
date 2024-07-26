<template>
    <li>
        <div :class="keys.NAME">{{ name }}</div>
        <div :class="keys.EMAIL">{{ email }}</div>
        <div :class="keys.CREATED_AT">{{ createdAt }}</div>
        <div :class="keys.UPDATED_AT">{{ updatedAt }}</div>
        <div><button>Edit</button></div>
        <div><button @click="del">Delete</button></div>
    </li>
</template>
<script>
import mixins from "@/common/mixins";

export default {
    mixins: [mixins],
    props: [
        "id",
        "name",
        "email",
        "createdAt",
        "updatedAt",
        "isAdmin",
    ],
    methods: {
        del() {
            this.$emit("del", this.id);
        }
    }
};
</script>

<style scoped>
li {
    padding: 20px;
    background-color: rgb(214, 214, 214);
    display: grid;
    grid-template-columns: 25% 25% 20% 20% 5% 5%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
}

li > div {
    line-height: 2;
    width: max-content
}

.name {
    font-weight: 500;
}
</style>