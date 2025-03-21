<template>
    <li>
        <div class="container-img photo">
            <img :src="photo || require('@/assets/common/user_placeholder.png')" alt="Foto de um motorista"/>
        </div>
        <div class="name" :title=name>{{ formatField(name, 20) }}</div>
        <div class="email">{{ email }}</div>
        <div class="created_at">Criado em {{ createdAt.toLocaleString("pt-BR") }}</div>
        <div class="updated_at">Atualizado em {{ updatedAt.toLocaleString("pt-BR") }}</div>
        <div 
            class="type" 
            :style="{backgroundColor: isAdmin ? 'var(--yellow-2)' : 'var(--green-2)'}"
        >
            {{ isAdmin ? 'Admin' : 'Comum' }}
        </div>
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
import { formatField, getNow } from "@/common/utils";


export default {
    mixins: [mixins],
    props: [
        "id",
        "name",
        "email",
        "photo",
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
        formatField,
    }
};
</script>

<style scoped>
li {
    padding: 15px;
    border-radius: 5px;
    background-color: var(--gray-2);
    display: grid;
    grid-template-rows: 30px 15px 10px 10px 50px 62px;
    grid-template-columns: 200px 1fr 100px;
    row-gap: 12px;
    column-gap: 15px;
    grid-template-areas: 
        'photo_____ name______ type______'
        'photo_____ email_____ .'
        'photo_____ created_at .'
        'photo_____ updated_at .'
        'photo_____ . .'
        'photo_____ . btns______'
    ;
    box-shadow: 
        0px 0px 0px rgba(3, 7, 18, 0.02),
        0px 0px 2px rgba(3, 7, 18, 0.03),
        0px 0px 4px rgba(3, 7, 18, 0.05),
        0px 0px 6px rgba(3, 7, 18, 0.06),
        0px 0px 16px rgba(3, 7, 18, 0.08)
    ;

}

.name {
    font-weight: 500;
    font-size: 30px;
}

.type {
    /* align-self: end; */
    color: white;
    height: max-content;
    width: max-content;
    justify-self: end;
    padding: 5px;
    border-radius: 5px;
}

.container-img {
    display: flex;
    width: 200px;
    height: 250px;
}

.container-img > img {
    background-color: #e2e2e284;
    border-radius: 5px;
    object-fit: cover;
    width: 200px;
    height: 237px;
}

button {
    border: transparent;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    align-items: center;
    color: white;
}

.edit {
    background-color: var(--yellow-1);
}

.del {
    background-color: var(--red-1);
}

.btns {
    display: flex;
    justify-content: end;
    gap: 10px;
    align-items: end;
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

.photo {
    grid-area: photo_____;
}

.email {
    grid-area: email_____;
}

.type {
    grid-area: type______;
}

.btns {
    grid-area: btns______;
}

.updated_at {
    grid-area: updated_at;
}

.created_at {
    grid-area: created_at;
}
</style>