<template>
    <div class="container">
        <div :class="$style.internal">
            <input 
                type="text" 
                placeholder="Nome"
                :value="name"
                @input="$emit('update:name', $event.target.value)"
            />
        </div>
        <iSelect
            :opts="$store.state.stockTypeMod.stockTypes"
            label=""
            placeholder="Tipo"
            name="type"
            :errors="[]"
            icon="dolly-solid"
            v-model="kind"
        />
        <div :class="$style.internal">
            <input 
                type="text" 
                placeholder="Quantidade"
                @input="onNumber" 
                :value="quantity"
                @change="$emit('update:quantity', Number($event.target.value))"
            />
        </div>
        <div :class="$style.internal">
            <input 
                type="text" 
                placeholder="Peso"
                @input="onNumber"
                :value="weight"
                @change="$emit('update:weight', Number($event.target.value))"
            />
        </div>
        <button type="button" :onclick="add">+</button>
        <button v-show="tot !== 1" type="button" :onclick="del">x</button>
    </div>
</template>
<script>

import iSelect from "./iSelect.vue";
import styles from "@/global/bInput.module.css";


export default {
    data: () => ({
        kind: 1
    }),

    props: [
        "id",
        "name",
        "quantity",
        "weight",
        "type",
        "tot"
    ],
    computed: {
        $style() {
            return styles;
        },
    },
    methods: {
        onNumber(e) {
            e.target.value = e.target.value.replace(/\D/g, "");
        },
        add() {
            this.$emit("add", null);
        },
        del() {
            this.$emit("del", this.id);
        }
    },
    components: {
        iSelect,
    },
};
</script>
<style scoped>
.container {
    display: flex;
    gap: 5px;
    align-items: center;
    margin-bottom: 20px;
}

.container > div:nth-child(2) {
    transform: translateY(3px);
}
</style>