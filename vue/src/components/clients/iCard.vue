<template>
    <li>
        <div :class="clientForm.NAME.NAME">{{ name }}</div>
        <div :class="clientForm.CNPJ.NAME">{{ CNPJ }}</div>
        <div :class="clientForm.ADDRESS.NAME" :title="`${address} - ${formattedCEP}`">{{ address }}</div>
        <div :class="clientForm.CELLPHONE.NAME">{{ cellphone }}</div>
        <div>
            <button @click="edit">
                Edit
            </button>
        </div>
    </li>
</template>

<script>
import mixins from "@/common/mixins";

export default {
    mixins: [mixins],
    props: [
        "id",
        "name",
        "CNPJ",
        "address",
        "CEP",
        "cellphone",
    ],
    computed: {
        formattedCEP() {
            return `${this.CEP.substring(0, 5)}-${this.CEP.substring(5, 8)}`;
        }
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },
    }
};
</script>

<style scoped>
li {
    padding: 20px;
    background-color: rgb(214, 214, 214);
    display: grid;
    grid-template-columns: 25% 25% 25% 20% 5%;
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

.address {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    max-width: 180px;
}
</style>