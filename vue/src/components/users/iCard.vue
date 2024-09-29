<template>
    <li>
        <div class="name">{{ name }}</div>
        <div class="email">{{ email }}</div>
        <div class="createdAt">{{ createdAt.toLocaleString("pt-BR") }}</div>
        <div class="updatedAt">{{ updatedAt.toLocaleString("pt-BR") }}</div>
        <div class="btns">
            <button
                class="edit"
                @click="edit"
                :disabled="((getNow() - createdAt) / (1000 * 60)) > 60"
            >
                    <iSvg 
                        :src="require('@/assets/icons/pencil-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
            </button>
            <button 
                class="del"
                @click="del" 
                :disabled="$store.state.userMod.logged.id === id"
            >
                    <iSvg 
                        :src="require('@/assets/icons/trash-solid.svg')"
                        width="16" 
                        height="16"
                        fill="white"
                    />
            </button>
        </div>
    </li>
</template>
<script>
import mixins from "@/common/mixins";
import { getNow } from "@/common/utils";


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
        edit() {
            this.$emit("edit", this.id);
        },

        del() {
            this.$emit("del", this.id);
        },
        getNow,
    }
};
</script>

<style scoped>
li {
    padding: 20px;
    display: grid;
    grid-template-columns: 25% 25% 20% 20% 10%;
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
    padding: 5px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    color: white;
}

.edit {
    background-color: var(--yellow-1);
}

.del {
    background-color: var(--red-1);
    color: white;
}

.btns {
    display: flex;
    justify-content: center;
    gap: 10px;
}

button:hover {
    transition: 0.3s;
    opacity: 0.8;
}

button:disabled {
    background-color: var(--gray-1);
    cursor: not-allowed;
}

button:disabled svg {
    fill: var(--gray-3);
}
</style>