<template>
    <div class="modal fade" id="modal-default"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Categoria</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('clear')">
                            <span aria-hidden="true" >&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text"
                                   id="name"
                                   :class="[
                                   Object.keys(errors).length > 0 && hasError('name', errors) ? 'is-invalid' : '',
                                   'form-control'
                                   ]"
                                   v-model="form.name">
                            <div
                                :class="Object.keys(errors).length > 0 && hasError('name', errors) ? 'invalid-feedback' : '' "
                                v-show="Object.keys(errors).length > 0 && hasError('name', errors)"
                            >
                                {{ getFirstErrors('name', errors) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción </label>
                            <input type="text" id="description"
                                   class="form-control"
                                   v-model="form.description">
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="$emit('clear')">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click.prevent="saveDataForm"> {{ btnTextAction }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import mixinErrors from "../mixins/errors";
  export default {
    name: "CategoryModal",
      mixins: [
          mixinErrors
      ],
    data() {
        return {

        }
    },
    computed: {
        form() {
            return this.$parent.form
        },
        btnTextAction() {
            if (!this.$parent.btnAction)  {
                return  'Actualizar'
            }
            return 'Guardar'
        },
        errors() {
            return this.$parent.errors
        }
    },

    methods: {
        saveDataForm() {
            if (this.btnTextAction === 'Guardar') {
                return this.storeCategory()
            }
            return this.updateCategory()
        },
        storeCategory() {
            return axios.post('/categories', this.form)
                .then(res => {
                    this.$emit('clear')
                    this.$emit('reloadCategories')
                    this.hideModal()
                })
                .catch( error =>  {
                    this.$parent.errors = error.response.data.errors
                })
        },
        updateCategory() {
            return axios.put(`/categories/${this.form.id}`, this.form)
                .then(res => {
                    this.$emit('clear')
                    this.$emit('reloadCategories')
                    this.hideModal()
                })
                .catch( error =>  {
                    this.$parent.errors = error.response.data.errors
                })
        },
        hideModal() {
            $('#modal-default').modal('hide')
        }
    }

  }
</script>

<style scoped>

</style>
