import { reactive } from 'vue'

const state = reactive({
    visible: false,
    message: '',
    resolve: null,
})

export function useConfirm() {
    const confirm = (message) => {
        state.message = message
        state.visible = true
        return new Promise((resolve) => {
            state.resolve = resolve
        })
    }

    const accept = () => {
        state.visible = false
        state.resolve?.(true)
    }

    const reject = () => {
        state.visible = false
        state.resolve?.(false)
    }

    return { state, confirm, accept, reject }
}