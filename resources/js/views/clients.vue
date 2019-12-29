<template>

    <div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">CLIENTS</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">CLIENTS</li>
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
                                <h3 class="card-title">Nouveau client</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                                <div class="card-body" style="height: 700px;">
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
                                        <label for="date_naissance" class="col-sm-2 col-form-label">Date naissance</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required autocomplete="date_naissance" autofocus placeholder="Date naissance" v-model="form.user.date_naissance">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('date_naissance')" v-text="form.errors.get('date_naissance')"></span>
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
                                    <div class="form-group row">
                                        <label for="profession" class="col-sm-2 col-form-label">Profession</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="profession" name="profession" required autocomplete="profession" autofocus placeholder="Profession" v-model="form.profession">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('profession')" v-text="form.errors.get('profession')"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="employeur" class="col-sm-2 col-form-label">Employeur</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="employeur" name="employeur" required autocomplete="employeur" autofocus placeholder="Employeur" v-model="form.employeur">
                                            <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('employeur')" v-text="form.errors.get('employeur')"></span>
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info" :disabled="form.errors.any()">Ajouter client</button>
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
                                <h3 class="card-title">LISTE DES CLIENTS</h3>

                                <div class="card-tools">
                                    <form class="form-horizontal" @submit.prevent="onSearch" @keydown="form.errors.clear()">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

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
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Sexe</th>
                                            <th>Date naissance</th>
                                            <th>Adresse</th>
                                            <th>Telephone</th>
                                            <th>Email</th>
                                            <th>Situation matrimoniale</th>
                                            <th>Profession</th>
                                            <th>Employeur</th>
                                            <th>Commune</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="client in clients.data "  :key="client.id" >

                                                <td >{{client.id}}</td>
                                                <td>{{client.user.nom}}</td>
                                                <td>{{client.user.prenom}}</td>
                                                <td>{{client.user.sexe}}</td>
                                                <td>{{client.user.date_naissance}}</td>
                                                <td>{{client.user.adresse}}</td>
                                                <td>{{client.user.telephone}}</td>
                                                <td>{{client.user.email}}</td>
                                                <td>{{client.user.situation_matrimoniale}}</td>
                                                <td>{{client.profession}}</td>
                                                <td>{{client.employeur}}</td>
                                                <td>{{client.user.commune.nom}}</td>
                                        </tr>
                                        <tr  >

                                                <td >1</td>
                                                <td>COFFI</td>
                                                <td>Comlan</td>
                                                <td>Masculin</td>
                                                <td>03-09-1992</td>
                                                <td> Addresse </td>
                                                <td>99943454</td>
                                                <td>coffi.comlan@gmail.com</td>
                                                <td>Marié</td>
                                                <td>Maçon</td>
                                                <td>Pas d'employeur</td>
                                                <td>Abomey-Calavi</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >2</td>
                                                <td>MENSAH</td>
                                                <td>Olga</td>
                                                <td>Feminin</td>
                                                <td>03-09-1996</td>
                                                <td> Addresse </td>
                                                <td>98900454</td>
                                                <td>olga.girl@gmail.com</td>
                                                <td>Célibataire</td>
                                                
                                                <td>Agriculteur</td>
                                                <td>Pas d'employeur</td>
                                                <td>Gogounou</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >3</td>
                                                <td>AKOUÉ</td>
                                                <td>Dimitri</td>
                                                <td>Masculin</td>
                                                <td>03-09-1987</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>Pas d'email </td>
                                                <td>marié</td>
                                                <td>Artisan</td>
                                                <td>Pas d'employeur</td>
                                                <td>Bohicon</td>
                                            
                                        </tr>
                                        <tr  >

                                                <td >4</td>
                                                <td>TOSAH</td>
                                                <td>Ingrid</td>
                                                <td>Feminin</td>
                                                <td>03-09-1991</td>
                                                <td> Addresse </td>
                                                <td>98900454</td>
                                                <td>olga.girl@gmail.com</td>
                                                <td>Célibataire</td>
                                                
                                                <td>Vendeuse</td>
                                                <td>Pas d'employeur</td>
                                                <td>Malanville</td>
                                            
                                        </tr>

                                        <tr  >

                                                <td >5</td>
                                                <td>VIGNI</td>
                                                <td>Elodie</td>
                                                <td>Feminin</td>
                                                <td>03-09-1967</td>
                                                <td> Addresse </td>
                                                <td>67123454</td>
                                                <td>Pas d'email </td>
                                                <td>marié</td>
                                                <td>Commerçante</td>
                                                <td>Pas d'employeur</td>
                                                <td>Bohicon</td>
                                            
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
                clients: [],
                users: [],
                
                departements: [],
                communes: [],
                form: new Form({
                    'id':'',
                    'profession':'',
                    'employeur': '',
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
            axios.get('http://localhost:8000/api/clients/')
                .then(data => this.clients = data.data)
                .catch(error => console.log(error));
                
        },

        methods: {
            onSubmit(){
                //this.form.password_confirmation = this.form.password; // Temp for this form only.
                this.form
                    .post('http://localhost:8000/api/clients')
                    .then(client => this.clients.push(client));
            },
            onSearch(){
                 // Temp for this form only.
                this.form
                    .post('api/clients')
                    .then(client => this.clients.push(client));
            },
            getDepartements(){
                 // Temp for this form only.
                axios.get('http://localhost:8000/api/departements')
                .then(({data}) => this.departements = data.data);
            },

            getCommunes(departement){
                 // Temp for this form only.
                axios.get('http://localhost:8000/api/departements/'+departement+'/communes')
                .then(({data}) => this.communes = data.data);
            }
        }
    }

</script>