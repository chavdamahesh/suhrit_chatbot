<?php
include '../../config/db.php';  // Adjust path if needed

// Fetch users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
session_start();
$successMessage = '';
if (isset($_SESSION['success'])) {
  $successMessage = $_SESSION['success'];
  unset($_SESSION['success']); // Clear the message after showing
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ChatBot</title>

  <!-- Google Font: Source Sans Pro -->
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="../../plugins/fontawesome-free/css/all.min.css" />
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css" />
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include './components/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Manage Customers</h3>
            <div class="card-tools d-flex gap-2 align-items-center">
              <!-- Add Customer Button -->
              <a href="addCustomer.php" class="btn btn-primary btn-sm mr-5">
                <i class="fas fa-user-plus"></i> Add Customer
              </a>

              <!-- Search Input -->
              <div class="input-group input-group-sm" style="width: 200px;">
                <input
                  type="text"
                  name="table_search"
                  class="form-control float-right"
                  placeholder="Search" />
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>

          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0" style="height: 350px;">
            <?php if (!empty($successMessage)) : ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                <?= htmlspecialchars($successMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <table class="table table-head-fixed text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Website name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['website']) ?></td>
                    <td>
                      <a href="editCustomer.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <a href="../../../api/deleteCustomer.php?id=<?= $user['id'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this customer?');">
                        <i class="fas fa-trash"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block"><b>Version</b> 3.1.0</div>
    <strong>Copyright &copy; 2025 <a href="https://">Chatbot</a>.</strong>
    All rights reserved.
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
  <script>
    $(document).ready(function() {
      function loadOptionTrees() {
        $.getJSON("get-options.php", function(data) {
          const tbody = $("#options-body");
          tbody.empty(); // Clear previous rows

          data.forEach(function(item) {
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
      $("#table-search").on("keyup", function() {
        const value = $(this).val().toLowerCase();
        $("#options-body tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });

      // Edit button click handler
      $(document).on("click", ".edit-btn", function() {
        const id = $(this).data("id");

        $.getJSON(`get-options.php?id=${id}`, function(data) {
          $("#tree-id").val(data.id); // hidden field
          $("#tree-title").val(data.title);

          const tree = JSON.parse(data.tree_data);
          renderTreeFromData(tree);
        });
      });

      // Delete button click handler
      $(document).on("click", ".delete-btn", function() {
        const id = $(this).data("id");

        if (confirm("Are you sure you want to delete this item?")) {
          $.ajax({
            url: "delete-option.php",
            type: "POST",
            data: {
              id: id
            },
            success: function(response) {
              // Refresh the table
              loadOptionTrees();
              alert("Item deleted successfully.");
            },
            error: function() {
              alert("Failed to delete item.");
            },
          });
        }
      });

      // Function to render the tree (you should already have a render function—this just plugs into it)

      function renderTreeFromData(treeData) {
        $("#selected-options").empty();
        // Recursively load the tree into your builder interface
        treeData.forEach((node) => {
          const block = buildNode(
            node.option || node.description,
            node.children || []
          );
          $("#selected-options").append(block);
        });
        $(".dynamic-select").select2({
          tags: true,
          width: "100%"
        });
      }

      // Reuse your existing buildNode() and tree rendering logic here
    });

    function buildNode(label, children = []) {
      let isAdding = true; // Initially, we are in "Add" mode

      const isOption = children.length > 0 || label !== undefined;
      const inputClass = isOption ? "option-input" : "description-input";

      // Create the input field with the toggle button
      const inputField = `
        <div class="input-wrapper d-flex align-items-center">
            <input type="text" class="${inputClass} form-control my-2" value="${label}" ${
          isAdding ? "" : "disabled"
        }>
            <button class="btn btn-primary btn-sm toggle-btn ms-2">${
              isAdding ? "Add" : "Stop"
            }</button>
        </div>
    `;

      const node = $(`
        <div class="node border p-2 my-2">
            ${inputField}
            <div class="children pl-3"></div>
        </div>
    `);

      // Set data-parent for the current node (this would be null for root nodes)
      node.attr("data-parent", label);

      // Now, handle appending the child nodes recursively
      children.forEach((child) => {
        const childNode = buildNode(
          child.option || child.description,
          child.children || []
        );
        // Set the parent relationship for the child node
        childNode.attr("data-parent", label);
        node.find(".children").append(childNode);
      });

      // Add event listener for the toggle button
      node.find(".toggle-btn").on("click", function() {
        if (isAdding) {
          // "Add" mode: Allow adding a child node
          const newLabel = prompt("Enter a label for the new child:");

          if (newLabel) {
            // Create the new child node with the new label
            const newChildNode = buildNode(newLabel, []);

            // Append the new child node to the current node's children container
            node.find(".children").append(newChildNode);
          }
        } else {
          // "Stop" mode: Save the description (no more children)
          node.find("input").prop("disabled", true);
          $(this).text("Add"); // Change button text back to "Add"

          // Now, we disable the toggle button so no further changes can be made
          alert(`Node "${label}" saved as a description.`);
        }

        // Toggle the button text between "Add" and "Stop"
        isAdding = !isAdding;
        $(this).text(isAdding ? "Add" : "Stop");
      });

      return node;
    }

    function getTreeAsJson() {
      function parseNode($node) {
        const option = $node.find("> .option-input").val();
        const description = $node.find("> .description-input").val();
        const parent = $node.data("parent") || null;

        const children = [];
        $node.find("> .children > .node").each(function() {
          children.push(parseNode($(this)));
        });

        if (option) {
          return {
            option: option,
            parent: parent,
            children: children
          };
        } else if (description) {
          return {
            description: description,
            parent: parent,
            children: children,
          };
        }
      }

      const tree = [];
      $("#selected-options > .node").each(function() {
        tree.push(parseNode($(this)));
      });

      return tree;
    }
    $("#update-btn").on("click", function() {
      const id = $("#tree-id").val();
      const title = $("#tree-title").val();
      const treeData = getTreeAsJson();

      $.ajax({
        url: "update-option.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
          id,
          title,
          tree_data: treeData
        }),
        success: function() {
          alert("Updated successfully");
          loadOptionTrees();
        },
        error: function() {
          alert("Update failed");
        },
      });
    });
  </script>
  <script>
    // Auto-dismiss after 1 second (1000 ms)
    setTimeout(() => {
      const alertElem = document.getElementById('success-alert');
      if (alertElem) {
        // Bootstrap 5 requires removing 'show' class and then dismissing
        alertElem.classList.remove('show');
        // Remove the alert from DOM after animation (fade)
        setTimeout(() => alertElem.remove(), 150);
      }
    }, 1000);
  </script>

</body>

</html>