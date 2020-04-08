<template>
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Product List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Products</h3>
                        <button data-toggle="modal" data-target="#exampleModal" class="btn btn-primary" style="float:right;">Add Product</button>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Barcode</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Supplier</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.product_id">
                                    <td>{{product.product_id}}</td>
                                    <td>{{product.barcode}}</td>
                                    <td>{{product.price}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>{{product.supplier.supplier_name}}</td>
                                    <td>{{product.category}}</td>
                                    <td>
                                        <a href="#"  data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-plus"></i>
                                        </a>
                                        &emsp;
                                        <a href="#"  data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        &emsp;
                                         <a href="#"  data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <!-- Modal-->
         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="addProduct">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Product Name</label>
                        <input v-model="product_name" required type="text" class="form-control" id="product" placeholder="Product Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Barcode</label>
                        <input v-model="barcode" type="text" required class="form-control" id="barcode" placeholder="Barcode">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Price</label>
                        <input v-model="price" type="text" required class="form-control" id="price" placeholder="In Php">
                    </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Supplier</label>
                        <v-select :options="suppliers"  :required="!supplier" v-model="supplier" name="supplier"></v-select>
                    </div>
                     <div class="form-group">
                        <label for="exampleInputEmail1">Category</label>
                        <v-select :options="categories"  :required="!category" v-model="category"></v-select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
                </div>
            </div>
            </div>
         <!-- Modal -->
    </section>
</template>

<script>
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css'
    export default {
        data(){
            return{
                editMode: false,
                suppliers:[],
                products:[],
                product_name:'',
                supplier:'',
                category:'',
                barcode:'',
                categories:['Phone','Laptop','Computers','Appliances'],
                category:'',
                price:'',
                errorInputs:[]
            }
        },
        methods:{
            getSuppliers(){
                axios.get('/getSuppliersCombo')
                    .then((res)=>{
                        this.suppliers = res.data
                        this.myTable()
                    }).catch((err)=>{
                        console.log(err)
                    })
            },
            addProduct(){
                axios.post('/addProduct',{
                    product_name: this.product_name,
                    quantity: 0,
                    supplier: this.supplier.id,
                    category: this.category,
                    barcode: this.barcode,
                    price: this.price
                }).then((res)=>{
                    this.clearValues()
                    console.log(this.supplier.id)
                    $('#exampleModal').modal('hide')
                    toastr.success('Product Added!')
                }).catch((res)=>{
                    toastr.error(res.message+' Check your Inputs')
                })
            },
            getProducts(){
                axios.get('/getProducts')
                    .then((res)=>{
                        this.products = res.data
                    }).catch((err)=>{
                        console.log(err)
                    })
            },
            myTable(){
               $(document).ready( function () {
                     $('#myTable').DataTable();
                  });
            },
            clearValues(){
                this.product_name=''
                this.supplier=''
                this.category= ''
                this.barcode=''
                this.quantity=''
                this.price=''
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            this.getProducts()
            this.getSuppliers()
        }
    }
</script>