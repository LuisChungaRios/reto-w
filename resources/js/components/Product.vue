<template>
    <div class="row">
        <div class="row my-3">
            <div class="col-sm-12 col-md-4">
                <button class="btn btn-primary"   @click="showModal(true)">Crear</button>
            </div>
        </div>
        <div class="col-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr >
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Precio</th>
                            <th>Descuento</th>
                            <th>Precio (IGV + descuento)</th>
                            <th>Imagen</th>
                            <th >Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(product) in products" :key="product.id">
                            <td> {{ product.name }} </td>
                            <td> {{ product.description }} </td>
                            <td> {{ product.price }} </td>
                            <td> {{ product.discount }} % </td>
                            <td>S/  {{ getDiscount(product.price, product.discount) }} </td>
                            <td width="30" height="30">
                                <img :src="'/storage/' + product.image" class="img-fluid">
                            </td>
                            <td>
                                <button class="btn btn-secondary" @click="showModal(false, product)" >Editar</button>
                                <button class="btn btn-danger" @click="deleteProduct(product.id)">Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="changePage(page)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Sig</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <product-modal @clear="clearForm" @reloadProducts="getProducts(1)"  ></product-modal>
    </div>
</template>

<script>
    import ProductModal from "./ProductModal";
    import discountProducts from "../mixins/discountProducts";
  export default {
        name: "Product",
        components: {
            ProductModal
        },
        mixins: [
          discountProducts
        ],
        data() {
            return {
                categories: {},
                errors: {},
                form: {
                    name: '',
                    description: '',
                    id: null,
                    price: '',
                    discount: 0,
                    image: '',
                    category_id: null
                },
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                products: [],
                btnAction: false
            }
        },
      computed: {
          isActived: function(){
              return this.pagination.current_page;
          },
          pagesNumber: function() {
              if(!this.pagination.to) {
                  return [];
              }

              let from = this.pagination.current_page - this.offset;
              if(from < 1) {
                  from = 1;
              }
              let to = from + (this.offset * 2);
              if(to >= this.pagination.last_page){
                  to = this.pagination.last_page;
              }
              let pagesArray = [];
              while(from <= to) {
                  pagesArray.push(from);
                  from++;
              }
              return pagesArray;
          }
      },
      methods: {
            clearForm(){
                this.form.id = ''
                this.form.description = ''
                this.form.name = ''
                this.form.price =  ''
                this.form.discount = ''
                this.form.image =  ''
                this.form.category_id = ''
                this.btnAction = false
                this.errors = {}
            },
          getCategories() {
            axios.get('/categories_all').then(res => this.categories = res.data.data)
          },
          getProducts (page){
              axios.get(`/products?page=${page}`).then(  (response) =>  {
                  let res= response.data;
                  this.products = res.data.data;
                  this.pagination= res.pagination;
              })
                  .catch(function (error) {
                      console.log(error);
                  });
          },
          changePage(page){
              this.pagination.current_page = page;
              this.getProducts(page);
          },
          deleteProduct(id) {
              return axios.delete(`/products/${id}`).then( res => this.getProducts(1))
          },
          showModal(action, form = null) {
                console.log(form)
                this.btnAction = action
                this.form = form != null ? Object.assign({}, form) : this.form
              $('#modal-default').modal('show')
          },

      },
      mounted() {
            this.getProducts(1);
            this.getCategories();
      }
  }
</script>

<style scoped>

</style>
