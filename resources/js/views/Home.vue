<template>

    <div>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Contrats</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Contrats</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
       <!-- Main content -->
        <div class="content">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">LISTE DES CONTRATS</h3>

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
                                            <th>Réference contrat</th>
                                            <th>Garantie</th>
                                            <th>Prime</th>
                                            <th>Durée</th>
                                            <th>Date debut</th>
                                            <th>Date écheance</th>
                                            <th>Contrat validé</th>
                                            <th>Client</th>
                                            <th>Marchand</th>
                                            <th>Assuré</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                                <td >1</td>
                                                <td>A12A1</td>
                                                <td>1000000</td>
                                                <td>1000</td>
                                                <td>1 an </td>
                                                <td>03-09-2020</td>
                                                <td>03-09-2021</td>
                                                <td> Contrat non validé </td>
                                                <td>COFFI Comlan</td>
                                                <td>COFFI Comlan</td>
                                                <td>COFFI Jeanne</td>
                                        </tr>
                                        <tr>
                                                <td >2</td>
                                                <td>Y1B5</td>
                                                <td>1000000</td>
                                                <td>1000</td>
                                                <td>1 an </td>
                                                <td>23-12-2019</td>
                                                <td>23-12-2020</td>
                                                <td> Contrat validé </td>
                                                <td>TOSAH Ingrid</td>
                                                <td>MENSAH Olga</td>
                                                <td>TOSAH Firmin</td>
                                        </tr>
                                        <tr>
                                                <td >3</td>
                                                <td>Z12C3</td>
                                                <td>1000000</td>
                                                <td>1000</td>
                                                <td>1 an </td>
                                                <td>18-12-2019</td>
                                                <td>18-12-2020</td>
                                                <td> Contrat validé </td>
                                                <td>VIGNI Elodie</td>
                                                <td>MENSAH Olga</td>
                                                <td>VIGNI Fidelle</td>
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
    // VueJS components will run here.
</script>