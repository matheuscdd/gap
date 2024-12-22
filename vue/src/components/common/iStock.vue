<template>
    <div :class="['container', ignore ? 'ignore' : '']">
        <div>
            <div :class="{[$style.internal]: true, invalid: errors.name.length, med: true}">
                <iSvg 
                    :src="require(`@/assets/icons/tags-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    type="text" 
                    placeholder="Nome"
                    :value="name"
                    name="name"
                    :disabled="onlyLess"
                    @input="$emit('update:name', $event.target.value)"
                    @blur="outFocus"
                />
            </div>
            <ul v-show="errors.name.length" :class="$style.errors">
                <li v-for="el in errors.name" :key="el">
                    <legend>{{ el }}</legend>
                </li>
            </ul>
        </div>
        <iSelect
            :opts="$store.state.stockTypeMod.stockTypes"
            label=""
            placeholder="Tipo"
            name="type"
            :errors="[]"
            icon="box-solid"
            v-model="kind"
            :readonly="onlyLess"
        />
        <div v-show="kind === 2">
            <div :class="{[$style.internal]: true, combo: true, med: true}">
                <input 
                    type="text" 
                    placeholder="Largura"
                    name="width"
                    @input="onFloat" 
                    v-model="width"
                    @change="updateExtra"
                    :disabled="onlyLess"
                />
                <div class="box">&nbsp;</div>
                <input 
                    type="text" 
                    placeholder="Altura"
                    name="height"
                    @input="onFloat" 
                    v-model="height"
                    @change="updateExtra"
                    :disabled="onlyLess"
                />
            </div>
        </div>
        <div>
            <div :class="{[$style.internal]: true, invalid: errors.quantity.length, med: true}">
                <iSvg 
                    :src="require(`@/assets/icons/boxes-stacked-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    type="text" 
                    placeholder="Quantidade"
                    @input="onInt" 
                    :value="quantity"
                    name="quantity"
                    @blur="outFocus"
                    @change="$emit('update:quantity', Number($event.target.value))"
                />
            </div>
            <ul v-show="errors.quantity.length" :class="$style.errors">
                <li v-for="el in errors.quantity" :key="el">
                    <legend>{{ el }}</legend>
                </li>
            </ul>
        </div>
        <div>
            <div :class="{[$style.internal]: true, invalid: errors.weight.length, med: true}">
                <iSvg 
                    :src="require(`@/assets/icons/weight-hanging-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    type="text" 
                    placeholder="Peso kg"
                    @input="onInt"
                    :value="weight"
                    name="weight"
                    @blur="outFocus"
                    @change="$emit('update:weight', Number($event.target.value))"
                />
            </div>
            <ul v-show="errors.weight.length" :class="$style.errors">
                <li v-for="el in errors.weight" :key="el">
                    <legend>{{ el }}</legend>
                </li>
            </ul>
        </div>
        <button v-show="!onlyLess" type="button" :onclick="add" class="green">
            <div>
                <iSvg 
                    :src="require(`@/assets/icons/plus-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="white"
                />
            </div>
        </button>
        <button v-show="tot !== 1 && !onlyLess" type="button" :onclick="del" class="red">
            <div>
                <iSvg 
                    :src="require(`@/assets/icons/xmark-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="white"
                />
            </div>
        </button>
        <button v-show="onlyLess" type="button" :onclick="clean" class="gray">
            <div>
                <iSvg 
                    :src="require(`@/assets/icons/ban-solid.svg`)"
                    width="16" 
                    height="16"
                    fill="white"
                />
            </div>
        </button>
    </div>
</template>
<script>

import { verifyStock } from "@/common/validators";
import iSelect from "./iSelect.vue";
import styles from "@/global/bInput.module.css";


export default {
    data: () => ({
        kind: 1,
        width: "",
        height: "",
        errors: {
            name: [],
            quantity: [],
            weight: []
        }
    }),
    props: [
        "id",
        "name",
        "quantity",
        "ignore",
        "weight",
        "type",
        "extra",
        "tot",
        "onlyLess",
        "max", // TODO - esse max será um objeto que terá weight e quantity, lem
    ],
    created() {
        this.kind = this.type;
        if (!this.extra) return;
        const [ width, height ] = this.extra.split("|");
        this.width = width;
        this.height = height;
    },
    computed: {
        $style() {
            return styles;
        },
    },
    watch: {
        kind() {
            this.$emit("update:type", Number(this.kind));
            this.kind = Number(this.kind);
            if (this.kind === 2) return;
            this.$emit("update:extra", "");
            this.width = "";
            this.height = "";
        },
    },
    methods: {
        onInt(e) {
            e.target.value = e.target.value.replace(/\D/g, "");
            if (!this.onlyLess) return;
            const type = e.target.name;
            if (Number(e.target.value) <= this.max[type]) return;
            e.target.value = this.max[type];
        },
        onFloat(e) {
            this[e.target.name] = e.target.value
                .replace(/[^0-9.]/g, "")
                .replace(/\.(?=.*\.)/g, "");
        },
        add() {
            this.$emit("add", null);
        },
        del() {
            this.$emit("del", this.id);
        },
        clean() {
            this.$emit("update:ignore", !this.ignore);
        },
        outFocus(e) {
            const name = e.target.name;
            const errors = verifyStock(this, name);
            this.errors[name] = errors;
            return errors;
        },
        updateExtra() {
            if (!this.width.trim() || !this.height.trim()) return;
            this.$emit("update:extra", `${this.width}|${this.height}`);
        },
        verifyStock
    },
    components: {
        iSelect,
    },
};
</script>
<style scoped>
.ignore {
    opacity: 50%;
}

.container {
    display: flex;
    gap: 5px;
    align-items: center;
    margin-bottom: 20px;
}

.container > div, button {
    margin-bottom: 7px;
}

.red { 
    background-color: var(--red-1);
}

.gray { 
    background-color: var(--gray-3);
}

.red span {
    transform: rotate(45deg);
}

.green {
    background-color: var(--green-2);
}

button {
    border: none;
    outline: none;
    height: 30px;
    width: 30px;
    border-radius: 50%;
    color: white;
    font-weight: 700;
    cursor: pointer;
}

button > div {
    transform: translateY(1.5px);
}

ul {
    position: absolute;
    margin-top: 10px;
    color: var(--red-1);
    font-size: 10px;
}

.invalid {
    box-shadow: 0px 0px 0px 1px var(--red-1);
    color: var(--red-1);
}

.invalid svg, .invalid input, .invalid input::placeholder {
    color: var(--red-1);
}

.combo {
    display: flex;
    max-width: 230px;
}

.combo > input {
    width: 100px;
}

.combo > .box {
    background-color: var(--gray-1);
    width: 1px;
    height: 30px;
}

.med:focus-within input, .med:focus-within input::placeholder, .med:focus-within svg  {
    color: var(--blue-1);
}

.med:focus-within {
    box-shadow: 0px 0px 0px 1px var(--blue-1);
}

</style>