<template>
    <div class="modal fade" id="modal-default"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form enctype="multipart/form-data" >
                    <div class="modal-header">
                        <h4 class="modal-title">Products</h4>
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

                        <div class="form-group">
                                <label for="image">Imagen</label>
                                <input type="file"
                                       @change="onImageChange"
                                       id="image"
                                       :class="[
                                       Object.keys(errors).length > 0 && hasError('image', errors) ? 'is-invalid' : '',
                                       'form-control'
                                       ]"
                                       >
                                <div
                                    :class="Object.keys(errors).length > 0 && hasError('image', errors) ? 'invalid-feedback' : '' "
                                    v-show="Object.keys(errors).length > 0 && hasError('image', errors)"
                                >
                                    {{ getFirstErrors('image', errors) }}
                                </div>
                            </div>
                        <div class="form-group">
                                <label for="price">Precio</label>
                                <input type="number"
                                       id="price"
                                       :class="[
                                       Object.keys(errors).length > 0 && hasError('price', errors) ? 'is-invalid' : '',
                                       'form-control'
                                       ]"
                                       v-model="form.price">
                                <div
                                    :class="Object.keys(errors).length > 0 && hasError('price', errors) ? 'invalid-feedback' : '' "
                                    v-show="Object.keys(errors).length > 0 && hasError('price', errors)"
                                >
                                    {{ getFirstErrors('price', errors) }}
                                </div>
                            </div>
                        <div class="form-group">
                                <label for="discount">Descuento (%)</label>
                                <input type="number"
                                       min="0"
                                       id="discount"
                                       :class="[
                                       Object.keys(errors).length > 0 && hasError('discount', errors) ? 'is-invalid' : '',
                                       'form-control'
                                       ]"
                                       v-model="form.discount">
                                <div
                                    :class="Object.keys(errors).length > 0 && hasError('discount', errors) ? 'invalid-feedback' : '' "
                                    v-show="Object.keys(errors).length > 0 && hasError('discount', errors)"
                                >
                                    {{ getFirstErrors('discount', errors) }}
                                </div>

                        </div>
                        <div class="form-group">
                                <label for="category_id">Categoria</label>
                                <select name="category_id" id="category_id"
                                        :class="[
                                       Object.keys(errors).length > 0 && hasError('category_id', errors) ? 'is-invalid' : '',
                                       'form-control'
                                       ]"
                                        v-model="form.category_id"
                                >
                                    <option v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div
                                    :class="Object.keys(errors).length > 0 && hasError('category_id', errors) ? 'invalid-feedback' : '' "
                                    v-show="Object.keys(errors).length > 0 && hasError('category_id', errors)"
                                >
                                    {{ getFirstErrors('category_id', errors) }}
                                </div>
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
    window.axios.defaults.headers.common['Content-Type'] = 'multipart/form-data'
  export default {
    name: "ProductModal",
      mixins: [
          mixinErrors
      ],
    data() {
        return {

        }
    },
    computed: {
        categories() {
          return this.$parent.categories
        },
        form() {
            this.$parent.form.image = ''
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
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.form.image = e.target.files[0]
            console.log(e.target.files[0])
        },
        saveDataForm() {
            if (this.btnTextAction === 'Guardar') {
                return this.storeProduct()
            }
            return this.updateProduct()
        },
        createFormData() {
          let newForm= new FormData();
          newForm.append('image', this.form.image)
          newForm.append('name', this.form.name)
          newForm.append('description', this.form.description)
          newForm.append('id', this.form.id)
          newForm.append('price', this.form.price)
          newForm.append('discount', this.form.discount)
          newForm.append('category_id', this.form.category_id)

            return newForm
        },
        storeProduct() {
            return axios.post('/products', this.createFormData())
                .then(res => {
                    this.$emit('clear')
                    this.$emit('reloadProducts')
                    this.hideModal()
                })
                .catch( error =>  {
                    this.$parent.errors = error.response.data.errors
                })
        },
        updateProduct() {
            return axios.post(`/update_product/${this.form.id}`, this.createFormData())
                .then(res => {
                    this.$emit('clear')
                    this.$emit('reloadProducts')
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
