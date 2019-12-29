<template>

    <div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Marchands</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Marchands</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau marchand</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                <div class="card-body" style="height: 550px;">
                                    <div class="form-group row">
                                        <label for="nom" class="col-sm-2 col-form-label">Nom</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nom" name="nom" required autocomplete="nom" autofocus placeholder="Nom" v-model="form.user.nom">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('nom')" v-text="form.errors.get('nom')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="prenom" class="col-sm-2 col-form-label">Prenom</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="prenom" name="prenom" required autocomplete="prenom" autofocus placeholder="Prenom" v-model="form.user.prenom">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('prenom')" v-text="form.errors.get('prenom')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="sexe" class="col-sm-2 col-form-label">Sexe</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.sexe" name="sexe" id="sexe" class="form-control" required="true" >
                                                <option value="">Choisissez le sexe</option>
                                                <option value="Masculin">Masculin</option>
                                                <option value="Feminin">Feminin</option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('sexe')" v-text="form.errors.get('sexe')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="telephone" class="col-sm-2 col-form-label">Telephone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="telephone" placeholder="Telephone" v-model="form.user.telephone" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('telephone')" v-text="form.errors.get('telephone')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="email" placeholder="Email" v-model="form.user.email" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="situation_matrimoniale" class="col-sm-2 col-form-label">Situation matrimoniale</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.situation_matrimoniale" name="situation_matrimoniale" id="situation_matrimoniale" class="form-control" required="true" >
                                                <option value="">Choisissez la situation matrimoniale</option>
                                                <option value="Célibataire">Célibataire</option>
                                                <option value="Marié(e)">Marié(e)</option>
                                                <option value="Divorcé(e)">Divorcé(e)</option>
                                                <option value="Veuf(ve)">Veuf(ve)</option>
                                                <option value="Concubin(e)">Concubin(e)</option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('sexe')" v-text="form.errors.get('sexe')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="adresse" class="col-sm-2 col-form-label">Adresse</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="adresse" placeholder="Adresse" v-model="form.user.adresse" required>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('adresse')" v-text="form.errors.get('adresse')"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="departement" class="col-sm-2 col-form-label">Departement</label>
                                        <div class="col-sm-10">
                                            <select v-model="form.user.commune.departement.id" name="departement_id" id="departement_id" class="form-control" required="true" @click.prevent="getDepartements">
                                                <option value="">Choisissez le departement</option>

                                                <option v-for="departement in departements" :key="departement.id" :value="departement.id" >{{departement.nom}} 
                                                </option>
                                            </select>
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('departement_id')" v-text="form.errors.get('departement_id')"></span>
                                            
                                        </div>
                                    </div>
                                    
                                        <div class="form-group row">
                                            <label for="commune" class="col-sm-2 col-form-label">Commune</label>
                                            <div class="col-sm-10">
                                                <select v-model="form.user.commune.id" name="commune_id" id="commune_id" class="form-control" required="true" @click.prevent ="getCommunes(form.user.commune.departement.id)">
                                                    <option value="">Choisissez la commune</option>

                                                    <option v-for="commune in communes" :key="commune.id" :value="commune.id" >{{commune.nom}} 
                                                    </option>
                                                </select>
                                                <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('commune_id')" v-text="form.errors.get('commune_id')"></span>
                                                
                                            </div>
                                        </div>
                                    
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info" :disabled="form.errors.any()">Ajouter marchand</button>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">LISTE DES MARCHANDS</h3>

                                <div class="card-tools">
                                    <form class="form-horizontal" @click.prevent="getMarchands(form.id)" @keydown="form.errors.clear()">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="form.id" class="form-control float-right" placeholder="Search">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Matricule</th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Sexe</th>
                                            <th>Date naissance</th>
                                            <th>Adresse</th>
                                            <th>Telephone</th>
                                            <th>Email</th>
                                            <th>Situation matrimoniale</th>
                                            <th>Compte principal</th>
                                            <th>Commission</th>
                                            <th>Commune</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="marchand in marchands.data "  :key="marchand.id" >

                                                <td >{{marchand.id}}</td>
                                                <td>{{marchand.matricule}}</td>
                                                <td>{{marchand.user.nom}}</td>
                                                <td>{{marchand.user.prenom}}</td>
                                                <td>{{marchand.user.sexe}}</td>
                                                <td>{{marchand.user.date_naissance}}</td>
                                                <td>{{marchand.user.adresse}}</td>
                                                <td>{{marchand.user.telephone}}</td>
                                                <td>{{marchand.user.email}}</td>
                                                <td>{{marchand.user.situation_matrimoniale}}</td>
                                                <td>{{marchand.credit_virtuel}}</td>
                                                <td>{{marchand.commission}}</td>
                                                <td>{{marchand.user.commune.nom}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /.content -->

    </div>

</template>

<script>
    import axios from 'axios';
    export default {

        data() {

            return {
                marchands: [],
                users: [],
                code:'',
                
                departements: [],
                communes: [],
                form: new Form({
                    'id':'',
                    'matricule':'',
                    'commission': 0,
                    'credit_virtuel': 0,
                    'user':{
                        'id': '',
                        'nom': '',
                        'sexe': '',
                        'login': '',
                        'actif': true,
                        'email': '',
                        'prenom': '',
                        'adresse': '',
                        'prospect': false,
                        'telephone': '',
                        'date_naissance': '',
                        'situation_matrimoniale': '',
                        'commune':{
                            'id': 0,
                            'nom': '',
                            'departement':{
                                'id': 0,
                                'nom': '',
                            }
                        }
                    }
                })
            }

        },

        created() {
            axios.get('http://localhost:8001/api/marchands/')
                .then(data => this.marchands = data.data)
                .catch(error => console.log(error));
                
        },

        methods: {
            onSubmit(){
                //this.form.password_confirmation = this.form.password; // Temp for this form only.
                this.form
                    .post('http://localhost:8001/api/marchands')
                    .then(marchand => this.marchands.push(marchand));
            },
            onSearch(){
                 // Temp for this form only.
                this.form
                    .post('api/marchands')
                    .then(marchand => this.marchands.push(marchand));
            },
            getDepartements(){
                 // Temp for this form only.
                axios.get('http://localhost:8001/api/departements')
                .then(({data}) => this.departements = data.data);
            },

            getCommunes(departement){
                 // Temp for this form only.
                axios.get('http://localhost:8001/api/departements/'+departement+'/communes')
                .then(({data}) => this.communes = data.data);
            },
            getMarchands(marchand){
                 // Temp for this form only.
                axios.get('http://localhost:8001/api/marchands/'+1)
                .then(({data}) => this.marchands = data.success);
            }
        }
    }

</script>
