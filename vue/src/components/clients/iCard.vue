<template>
    <li>
        <div class="name">{{ name }}</div>
        <div>{{ CNPJ }}</div>
        <div class="address" :title="`${address} - ${formattedCEP}`">{{ formattedAddress }}</div>
        <div class="cellphone">{{ formattedCellphone }}</div>
        <div>
            <div class="btns">
                <button @click="edit">
                    <iSvg 
                        :src="require('@/assets/icons/pencil-solid.svg')"
                        width="16" 
                        height="16"
                        fill="currentColor"
                    />
                </button>
            </div>
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
        },
        formattedAddress(){
            return this.address.slice(0, 50) + "...";
        },
        formattedCellphone() {
            const ddd = this.cellphone.substring(0, 2);
            const first = this.cellphone.substring(2, 3);
            const second = this.cellphone.substring(3, 7);
            const third = this.cellphone.substring(7, 13);
            return `(${ddd}) ${first} ${second} ${third}`;
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
    display: grid;
    grid-template-columns: 25% 25% 25% 15% 10%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
    text-align: center;
    border-radius: 5px;
    border: 2px solid var(--gray-1);
    background-color: white;
}

li > div {
    line-height: 2;
}

.name {
    font-weight: 500;
}

button {
    border: transparent;
    cursor: pointer;
    background-color: var(--yellow-1);
    padding: 5px;
    border-radius: 5px;
    display: flex;
    align-items: center;
}

.btns {
    display: flex;
    justify-content: center;
}

.btns button:hover {
    transition: 0.3s;
    opacity: 0.8;
}
</style>