<template>
    <vDialog
        v-model="dialog"
        :max-width="options.width"
        :style="{ zIndex: options.zIndex, backdropFilter: 'blur(50px)' }"
        persistent
        color="red"
        opacity="0.1"
        ref="modal"
    >
    <div v-show="!!message" class="container">
        <div class="title">{{ isAlert ? 'Alerta' : 'Confirme' }}</div>
        <div class="body">{{ message }}</div>
        <div class="footer btns">
            <button v-show="!isAlert" @click="cancel" class="cancel">Cancelar</button>
            <button @click="agree" class="agree">Ok</button>
        </div>
    </div>
  </vDialog>
</template>
<script>
export default {
  provide: [
    "modal"
  ],
  data: () => ({
      dialog: false,
      resolve: null,
      message: null,
      isAlert: false,
      options: {
        width: 400,
        zIndex: 200,
      }
    }),
  methods: {
    open(message, isAlert = false) {
      this.dialog = true;
      this.isAlert = isAlert;
      this.message = message;
      return new Promise((resolve) => this.resolve = resolve);
    },
    update(message) {
        this.message = message;
    },
    agree() {
      this.resolve(true);
      this.dialog = false;
    },
    cancel() {
      this.resolve(false);
      this.dialog = false;
    }
  },
};
</script>
<style scoped>
.container {
    width: 400px;
    height: max-content;
    min-height: 160px;
    border-radius: 5px;
    background-color: #FFF;
    display: flex;
    flex-direction: column;
    padding-bottom: 10px;
    user-select: none;
    box-shadow:
        2.8px 2.8px 2.2px rgba(0, 0, 0, 0.02),
        6.7px 6.7px 5.3px rgba(0, 0, 0, 0.028),
        12.5px 12.5px 10px rgba(0, 0, 0, 0.035),
        22.3px 22.3px 17.9px rgba(0, 0, 0, 0.042),
        41.8px 41.8px 33.4px rgba(0, 0, 0, 0.05),
        100px 100px 80px rgba(0, 0, 0, 0.07)
    ;
}

.title {
    background-color: var(--gray-4);
    padding: 15px;
    font-size: 22px;
    margin-bottom: 15px;
}

.body {
    padding: 15px;
}

.footer {
    display: flex;
    align-items: end;
    justify-self: end;
    align-self: end;
    padding: 0 15px;
    flex-grow: 1;
    gap: 8px;
}

.btns button {
    font-size: 16px;
    border-radius: 5px;
    border: transparent;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 5px 10px;
}

.btns button:hover:not(:disabled), .btns button:active {
    transition: 0.3s;
    filter: brightness(2);
}

.btns .agree {
    background-color: var(--green-2);
}

.btns .cancel {
    background-color: var(--red-1);
}
</style>