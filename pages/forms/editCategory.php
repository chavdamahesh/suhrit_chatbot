<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ChatBot</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
  /* Style for the Select2 dropdown container */
  .select2-container--default .select2-selection--multiple {
    background-color: #f0f8ff; /* Light blue background */
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  /* Optional: Style selected tags inside Select2 */
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #00ffd0;  /* Bootstrap primary */
    color: #fff;
    border: none;
  }
</style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
     <?php include './components/sidebar.php'; ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Forms</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="advanced.php" class="btn btn-primary">Add</a></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Saved Option Trees</h3>
                  <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      <input type="text" id="table-search" class="form-control float-right" placeholder="Search">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-default">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 300px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="options-body">
                      <!-- Data rows will be injected here -->
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div id="edit-form-section" style="display: none;" class="card card-body mt-3">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <!-- Title -->
                        <label for="tree-title">Title</label>
                        <input type="text" class="form-control" id="tree-title" name="title"
                          placeholder="Enter your title">
                        <input type="hidden" id="edit-id" value="">

                        <!-- Options -->
                        <label for="tree-parents">Parent</label>
                        <select id="tree-parents" class="form-control" multiple="multiple">
                          <!-- Select2 options will be populated -->
                        </select>
                        <!-- Options -->
                        <label for="tree-options">Options</label>
                        <select id="tree-options" class="form-control" multiple="multiple">
                          <!-- Select2 options will be populated -->
                        </select>

                        <div id="selected-options" class="mt-2"></div>
                        <button id="save-options" class="btn btn-success mt-3">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.1.0
      </div>
      <strong>Copyright &copy; 2025 <a href="https://">Chatbot</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      function loadOptionTrees() {
        $.getJSON('get-options.php', function (data) {
          const tbody = $('#options-body');
          tbody.empty(); // Clear previous rows

          data.forEach(function (item) {
            const row = `
            <tr data-id="${item.id}">
              <td>${item.id}</td>
              <td>${item.title}</td>
              <td>
                <button class="btn btn-sm btn-primary edit-btn" data-id="${item.id}">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>`;
            tbody.append(row);
          });
        });
      }

      // Load on page load
      loadOptionTrees();

      // Search functionality
      $('#table-search').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $("#options-body tr").filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      // Edit button click handler
      $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $('#edit-id').val(id); // Set the hidden edit ID

        // Show the form
        $('#edit-form-section').slideDown();

        // Fetch option data
        $.getJSON(`get-options.php?id=${id}`, function (data) {
          $('#tree-title').val(data.title); // Fill in title

          const tree = JSON.parse(data.tree_data || '[]');

          // Populate Select2 options
          $('#tree-parents').empty(); // Clear previous
          tree.forEach(item => {
            $('#tree-parents').append(new Option(item.parent, item.parent, true, true));
          });

          $('#tree-parents').select2({
            tags: true,
            width: '100%',
            placeholder: "Select or type options",
            allowClear: true
          });
          // Populate Select2 options
          $('#tree-options').empty(); // Clear previous
          tree.forEach(item => {
            $('#tree-options').append(new Option(item.option, item.option, true, true));
          });

          $('#tree-options').select2({
            tags: true,
            width: '100%',
            placeholder: "Select or type options",
            allowClear: true
          });

          // If you're using nested options, render using your renderTreeFromData function:
          renderTreeFromData(tree);
        });
      });
      // Function to render the tree (you should already have a render function—this just plugs into it)
function renderTreeGroupedByTopParents(flatTree) {
  $('#selected-options').empty(); // Clear container

  // Step 1: Get all unique parents
  const uniqueParents = [...new Set(flatTree.map(item => item.parent))];

  // Step 2: For each parent, group matching items
  uniqueParents.forEach(parent => {
    const parentGroup = flatTree.filter(item => item.parent === parent);

    const parentDiv = $(`
      <div class="ml-2 mt-3 p-3 rounded" style="background-color: #f0f8ff;">
        <h5><strong>${parent}</strong></h5>
      </div>
    `);

    parentGroup.forEach(child => {
      const childBlock = $('<div class="ml-3 mb-3"></div>');

      const select = $(`
        <select class="form-control dynamic-select" style="width:auto;">
          <option selected>${child.option}</option>
        </select>
      `);
      childBlock.append(select);

      // Render descriptions from children
      if (Array.isArray(child.children)) {
        child.children.forEach(descNode => {
          const descInput = $(`<input type="text" class="form-control mt-2 ml-3" value="${descNode.description}" readonly>`);
          childBlock.append(descInput);
        });
      }

      parentDiv.append(childBlock);
    });

    $('#selected-options').append(parentDiv);
  });

  // Reinitialize Select2
  $('.dynamic-select').select2({ tags: true, width: 'auto' });
}

      // Reuse your existing buildNode() and tree rendering logic here
    });
  </script>

</body>

</html>