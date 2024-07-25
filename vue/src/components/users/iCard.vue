<template>
    <li>
        <div :class="NAME">{{ name }}</div>
        <div :class="EMAIL">{{ email }}</div>
        <div :class="CREATED_AT">{{ createdAt }}</div>
        <div :class="UPDATED_AT">{{ updatedAt }}</div>
        <div><button>Edit</button></div>
        <div><button @click="del">Delete</button></div>
    </li>
</template>
<script>
import consts from "@/common/mixins";

export default {
    mixins: [consts],
    props: [
        "id",
        "name",
        "email",
        "createdAt",
        "updatedAt",
        "isAdmin"
    ],
    methods: {
        del() {
            console.log(this.id);
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
}

.name {
    font-weight: 500;
}
</style>