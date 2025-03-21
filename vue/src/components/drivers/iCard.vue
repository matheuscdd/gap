<template>
    <li>
        <div class="container-img photo">
            <img :src="photo || require('@/assets/common/user_placeholder.png')" alt="Foto de um caminhão"/>
        </div>
        <iInternal class="name" legend="nome" :value="formatField(name, 28)"/>
        <iInternal class="CPF" legend="CPF" :value="CPF"/>
        <iInternal class="deliveries" legend="entregas realizadas" :value="deliveries"/>
        <iInternal class="updated_at" legend="atualização" :value="updatedAt.toLocaleString('pt-BR')"/>
        <div class="btns">
            <button
                class="edit"
                @click="edit"
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
                :disabled="Boolean(deliveries) || ((getNow() - createdAt) / (1000 * 60)) > 30"
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
import { formatField, getNow } from "@/common/utils";
import iInternal from "./iInternal.vue";

export default {
    mixins: [mixins],
    props: [
        "id",
        "name",
        "CPF",
        "photo",
        "deliveries",
        "createdAt",
        "updatedAt",
    ],
    components: {
        iInternal,
    },
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },

        del() {
            this.$emit("del", this.id);
        },
        getNow,
        formatField,
    }
};
</script>

<style scoped>
li {
    padding: 15px;
    border-radius: 5px;
    background-color: var(--green-7);
    display: grid;
    grid-auto-rows: 50px;
    width: max-content;
    grid-template-columns: 200px 400px 100px;
    row-gap: 12px;
    column-gap: 15px;
    grid-template-areas: 
        'photo_____ name______ btns______'
        'photo_____ CPF_______ CPF_______'
        'photo_____ updated_at updated_at'
        'photo_____ deliveries deliveries'
    ;
    box-shadow: 
        0px 0px 0px rgba(3, 7, 18, 0.02),
        0px 0px 2px rgba(3, 7, 18, 0.03),
        0px 0px 4px rgba(3, 7, 18, 0.05),
        0px 0px 6px rgba(3, 7, 18, 0.06),
        0px 0px 16px rgba(3, 7, 18, 0.08)
    ;

}

.container-img {
    display: flex;
    width: 200px;
    height: 250px;

}

.container-img > img {
    background-color: #e2e2e284;
    border-radius: 5px;
    width: 200px;
    height: 237px;
}

button {
    border: transparent;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    height: 45px;
    width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.edit {
    background-color: var(--yellow-1);
}

.del {
    background-color: var(--red-1);
}

.btns {
    margin-top: 6px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

button:hover:not(:disabled), button:active:not(:disabled) {
    transition: 0.3s;
    filter: brightness(2);
}

button:disabled {
    background-color: var(--gray-1);
    cursor: not-allowed;
}

button:disabled svg {
    fill: var(--gray-3);
}

.name {
    grid-area: name______;
}

.CPF {         
    grid-area: CPF_______;
}

.deliveries {
    grid-area: deliveries;
}

.updated_at { 
    grid-area: updated_at;
}

.btns {        
    grid-area: btns______;
}

.photo {       
    grid-area: photo_____;
}
</style>