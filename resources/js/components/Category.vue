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
                            <th >Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(category) in categories" :key="category.id">
                            <td> {{ category.name }} </td>
                            <td> {{ category.description }} </td>
                            <td>
                                <button class="btn btn-secondary" @click="showModal(false, category)" >Editar</button>
                                <button class="btn btn-danger" @click="deleteCategory(category.id)">Eliminar</button>
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
        <category-modal @clear="clearForm" @reloadCategories="getCategories(1)" ref="categoryModal" ></category-modal>
    </div>
</template>

<script>
    import CategoryModal from "./CategoryModal";

  export default {
        name: "Category",
        components: {
            CategoryModal
        },
        data() {
            return {
                errors: {},
                form: {
                    name: null,
                    description: null,
                    id: null
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
                categories: [],
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
                this.form.id = null
                this.form.description = null
                this.form.name = null
                this.btnAction = false
                this.errors = {}
            },
          getCategories (page){
              axios.get(`/categories?page=${page}`).then(  (response) =>  {
                  let res= response.data;
                  this.categories = res.data.data;
                  this.pagination= res.pagination;
              })
                  .catch(function (error) {
                      console.log(error);
                  });
          },
          changePage(page){
              this.pagination.current_page = page;
              this.getCategories(page);
          },
          deleteCategory(id) {
              return axios.delete(`/categories/${id}`).then( res => this.getCategories(1))
          },
          showModal(action, form = null) {
                this.btnAction = action
                this.form = form != null? Object.assign({}, form) : this.form
              $('#modal-default').modal('show')
          },

      },
      mounted() {
            this.getCategories(1);
      }
  }
</script>

<style scoped>

</style>
