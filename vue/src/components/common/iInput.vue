<template>
    <div :class="['external', {focused}, {empty}]">
        <label :for="id">{{ label }}</label>
        <div class="internal">
            <iSvg 
                :src="require(`@/assets/icons/${icon}.svg`)"
                width="16" 
                height="16"
                fill="currentColor"
            />
            <input 
                :id="id" 
                :placeholder="placeholder" 
                :type="type" 
                :value="modelValue" 
                @focusin="onFocus" 
                @blur="outFocus" 
                @input="updateValue"
            />
        </div>
        <ul v-show="errors.length" class="errors">
            <li v-for="el in errors" :key="el + getUUID()">
                <legend>{{ el }}</legend>
            </li>
        </ul>
    </div>
</template>

<script>
import { getUUID } from "@/common/utils";

export default {
    data: () => ({
        id: getUUID(),
        focused: false,
        empty: false,
    }),
    props: [
        "placeholder", 
        "icon", 
        "label", 
        "name", 
        "modelValue", 
        "type",
        "errors",
    ],
    watch: {
        modelValue(value) {
            this.empty = !value.length;
        }
    },
    methods: {
        onFocus() {
            this.focused = true;
        },
        outFocus() {
            this.focused = false;
            this.empty = !this.modelValue.length;
            this.$emit("validate", this.name);
        },
        updateValue(e) {
            this.$emit("update:modelValue", e.target.value);
        },
        getUUID
    }
};

</script>

<style scoped>
.external {
    color: var(--gray-1);
    margin-bottom: 16px;
}

.external.focused,
.external.focused input, 
.external.focused input::placeholder,
.external.focused svg,
.external.focused label {
    color: var(--blue-1);
}

.external.empty input, 
.external.empty input::placeholder,
.external.empty svg,
.external.empty label {
    color: var(--red-1);
}

.external, input, input::placeholder, svg {
    transition: var(--trans-1);
    color: var(--gray-1);
}

.external.focused .internal {
    box-shadow: 0px 0px 0px 1px var(--blue-1);
}

.external.empty .internal {
    box-shadow: 0px 0px 0px 1px var(--red-1);
}

.internal {
    transition: var(--trans-1) box-shadow;
    box-shadow: 0px 0px 0px 1px var(--gray-1);
    width: max-content;
    border-radius: 30px;
    padding: 5px 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

label {
    display: block;
    margin-bottom: 10px;
}

input {
    border: none;
}

input:focus {
    outline: none;
}

img {
    width: 12px;
}

.errors {
    font-weight: 500;
    margin-top: 5px;
    font-size: 14px;
    color: var(--red-1);
}

.errors li {
    margin-bottom: 5px;
}
</style>