<template>
    <section class="content">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Suppliers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Suppliers</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Suppliers</h3>
                          <button @click="addModal" class="btn btn-primary" style="float:right;">Add Supplier</button>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Supplier ID</th>
                                    <th>Supplier Name</th>
                                    <th>Supplier Address</th>
                                    <th>Supplier Email</th>
                                    <th>Supplier Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="supplier in suppliers" :key="supplier.supplier_id">
                                    <td>{{supplier.supplier_id}}</td>
                                    <td>{{supplier.supplier_name}}</td>
                                    <td>{{supplier.supplier_address}}</td>
                                    <td>{{supplier.supplier_email}}</td>
                                    <td>{{supplier.supplier_phone}}</td>
                                    <td>
                                        &emsp;
                                        <a href="#" >
                                        <i class="fa fa-edit"></i>
                                        </a>
                                        &emsp;
                                         <a href="#">
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="addSupplier">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Supplier Name</label>
                        <input v-model="supplier_name" required type="text" class="form-control" id="product" placeholder="Supplier Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Address</label>
                        <input v-model="supplier_address" type="text" required class="form-control" id="barcode" placeholder="Address">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input v-model="supplier_email" type="email" required class="form-control" id="price" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Phone</label>
                        <input v-model="supplier_phone" type="text" required class="form-control" id="price" placeholder="">
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
    export default {
        data(){
            return{
                suppliers:[],
                supplier_name:'',
                supplier_address:'',
                supplier_email:'',
                supplier_phone:'',
                editMode:false,
            }
        },
        methods:{
           async getSuppliers(){
               await axios.get('/getSuppliers')
                    .then((res)=>{
                        this.suppliers = res.data
                        this.myTable()
                    })
            },
            myTable(){
               $(document).ready( function () {
                     $('#myTable').DataTable();
                  });
            },
            clearValues(){
                this.supplier_name=''
                this.supplier_address=''
                this.supplier_email=''
                this.supplier_phone=''
            },
            addModal(){
                this.editMode = false
                this.clearValues()
                $('#exampleModal').modal('show')
            },
            addSupplier(){
                axios.post('/addSupplier',{
                    supplier_name: this.supplier_name,
                    supplier_address: this.supplier_address,
                    supplier_phone: this.supplier_phone,
                    supplier_email: this.supplier_email
                })
                    .then((res)=>{
                        this.clearValues()
                        $('#exampleModal').modal('hide')
                        toastr.success('Supplier Added!')
                        this.getSuppliers()
                    }).catch((res)=>{
                        toastr.error(res.message+ ' Check your inputs')
                    })
            }
        },
        created(){
            this.getSuppliers()
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
