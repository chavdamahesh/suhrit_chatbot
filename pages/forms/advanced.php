<?php
session_set_cookie_params(60);
ini_set('session.gc_maxlifetime', 60);
session_start();

// If session expired or missing, redirect to login
if (!isset($_SESSION['user_id'])) {
    // Optionally clear cookies if you want to fully logout
    setcookie("user_id", "", time() - 3600, "/");
    setcookie("email", "", time() - 3600, "/");
    setcookie("role", "", time() - 3600, "/");

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AdminLTE 3 | Advanced form elements</title>

    <!-- Google Font: Source Sans Pro -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="../../plugins/fontawesome-free/css/all.min.css"
    />
    <!-- daterange picker -->
    <link
      rel="stylesheet"
      href="../../plugins/daterangepicker/daterangepicker.css"
    />
    <!-- iCheck for checkboxes and radio inputs -->
    <link
      rel="stylesheet"
      href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css"
    />
    <!-- Bootstrap Color Picker -->
    <link
      rel="stylesheet"
      href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"
    />
    <!-- Tempusdominus Bootstrap 4 -->
    <link
      rel="stylesheet"
      href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"
    />
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css" />
    <link
      rel="stylesheet"
      href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"
    />
    <!-- Bootstrap4 Duallistbox -->
    <link
      rel="stylesheet"
      href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css"
    />
    <!-- BS Stepper -->
    <link
      rel="stylesheet"
      href="../../plugins/bs-stepper/css/bs-stepper.min.css"
    />
    <!-- dropzonejs -->
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css" />

    <style>
      .mt-4 {
        margin-top: 2.2rem !important;
      }
    </style>
  </head>

  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <!-- Main Sidebar Container -->
    <?php include './components/sidebar.php'; ?>


      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Enter Your Category</h3>

                <div class="card-tools">
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="collapse"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-tool"
                    data-card-widget="remove"
                  >
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="form-group">
                        <!-- title -->
                        <label for="tree-title">Title</label>
                        <input
                          type="text"
                          class="form-control"
                          id="tree-title"
                          name="title"
                          placeholder="Enter your title"
                        />
                      </div>
                      <!-- options -->
                      <label for="title">Options</label>
                      <select
                        id="tags"
                        class="form-control"
                        multiple="multiple"
                      >
                        <!-- nested Select2 tree structure -->
                      </select>
                      <div id="selected-options" class="mt-2"></div>
                      <button id="save-options" class="btn btn-success mt-3">
                        Save
                      </button>
                    </div>
                  </div>
                  <!-- /.col -->
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

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
    <!-- Select2 -->
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="../../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="../../plugins/moment/moment.min.js"></script>
    <script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="../../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="../../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- dropzonejs -->
    <script src="../../plugins/dropzone/min/dropzone.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        //Initialize Select2 Elements
        $(".select2bs4").select2({
          theme: "bootstrap4",
        });

        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", { placeholder: "dd/mm/yyyy" });
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", { placeholder: "mm/dd/yyyy" });
        //Money Euro
        $("[data-mask]").inputmask();

        //Date picker
        $("#reservationdate").datetimepicker({
          format: "L",
        });

        //Date and time picker
        $("#reservationdatetime").datetimepicker({
          icons: { time: "far fa-clock" },
        });

        //Date range picker
        $("#reservation").daterangepicker();
        //Date range picker with time picker
        $("#reservationtime").daterangepicker({
          timePicker: true,
          timePickerIncrement: 30,
          locale: {
            format: "MM/DD/YYYY hh:mm A",
          },
        });
        //Date range as a button
        $("#daterange-btn").daterangepicker(
          {
            ranges: {
              Today: [moment(), moment()],
              Yesterday: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days"),
              ],
              "Last 7 Days": [moment().subtract(6, "days"), moment()],
              "Last 30 Days": [moment().subtract(29, "days"), moment()],
              "This Month": [
                moment().startOf("month"),
                moment().endOf("month"),
              ],
              "Last Month": [
                moment().subtract(1, "month").startOf("month"),
                moment().subtract(1, "month").endOf("month"),
              ],
            },
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
          },
          function (start, end) {
            $("#reportrange span").html(
              start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
            );
          }
        );

        //Timepicker
        $("#timepicker").datetimepicker({
          format: "LT",
        });

        //Bootstrap Duallistbox
        $(".duallistbox").bootstrapDualListbox();

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        $(".my-colorpicker2").on("colorpickerChange", function (event) {
          $(".my-colorpicker2 .fa-square").css("color", event.color.toString());
        });

        $("input[data-bootstrap-switch]").each(function () {
          $(this).bootstrapSwitch("state", $(this).prop("checked"));
        });
      });
      // BS-Stepper Init
      document.addEventListener("DOMContentLoaded", function () {
        window.stepper = new Stepper(document.querySelector(".bs-stepper"));
      });

      // DropzoneJS Demo Code Start
      Dropzone.autoDiscover = false;

      // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
      var previewNode = document.querySelector("#template");
      previewNode.id = "";
      var previewTemplate = previewNode.parentNode.innerHTML;
      previewNode.parentNode.removeChild(previewNode);

      var myDropzone = new Dropzone(document.body, {
        // Make the whole body a dropzone
        url: "/target-url", // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
      });

      myDropzone.on("addedfile", function (file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function () {
          myDropzone.enqueueFile(file);
        };
      });

      // Update the total progress bar
      myDropzone.on("totaluploadprogress", function (progress) {
        document.querySelector("#total-progress .progress-bar").style.width =
          progress + "%";
      });

      myDropzone.on("sending", function (file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1";
        // And disable the start button
        file.previewElement
          .querySelector(".start")
          .setAttribute("disabled", "disabled");
      });

      // Hide the total progress bar when nothing's uploading anymore
      myDropzone.on("queuecomplete", function (progress) {
        document.querySelector("#total-progress").style.opacity = "0";
      });

      // Setup the buttons for all transfers
      // The "add files" button doesn't need to be setup because the config
      // `clickable` has already been specified.
      document.querySelector("#actions .start").onclick = function () {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
      };
      document.querySelector("#actions .cancel").onclick = function () {
        myDropzone.removeAllFiles(true);
      };
      // DropzoneJS Demo Code End
    </script>

    <!-- nested Select2 tree structure -->
    <script>
      $(document).ready(function () {
        $("#tags").select2({
          tags: true,
          width: "100%",
          placeholder: "Enter root options",
        });

        let optionTree = [];

        function renderTree() {
          $("#selected-options").empty();
          const selected = $("#tags").val();

          if (!selected || selected.length === 0) return;

          selected.forEach((option) => {
            const block = buildNode(option, []);
            $("#selected-options").append(block);
          });

          // Reinitialize all select2s after DOM update
          $(".dynamic-select").select2({ tags: true, width: "100%" });
        }

        function buildNode(label, children) {
          const wrapper = $('<div class="ml-3 mb-3 d-flex align-items-start">');
          const contentWrapper = $('<div style="flex:1"></div>');
          const title = $('<label class="font-weight-bold d-block">').text(
            label.charAt(0).toUpperCase() + label.slice(1)
          );
          let input = $(
            `<select class="form-control dynamic-select mt-2" multiple></select>`
          );
          const childContainer = $('<div class="child-container mt-2">');
          const button = $(
            `<button type="button" class="btn btn-primary btn-sm ml-2 mt-4">Stop</button>`
          );

          // Add existing children
          children.forEach((child) => {
            const opt = new Option(child.option, child.option, true, true);
            input.append(opt);
          });

          setTimeout(() => {
            input.select2({
              tags: true,
              width: "100%",
              placeholder: `Enter options for ${label}`,
            });
          }, 0);

          input.on("change", function () {
            const selected = $(this).val() || [];
            childContainer.empty();
            selected.forEach((value) => {
              const childBlock = buildNode(value, []);
              childContainer.append(childBlock);
            });
          });

          // Toggle logic
          button.on("click", function () {
            if (button.text() === "Stop") {
              // Replace select2 with textbox
              input.select2("destroy");
              const textBox = $(
                '<input type="text" class="form-control mt-2" placeholder="Enter Description">'
              );
              input.replaceWith(textBox);
              input = textBox;
              button.text("Add");
            } else {
              // Replace textbox with select2
              const newSelect = $(
                `<select class="form-control dynamic-select mt-2" multiple></select>`
              );
              setTimeout(() => {
                newSelect.select2({
                  tags: true,
                  width: "100%",
                  placeholder: `Enter options for ${label}`,
                });
              }, 0);
              input.replaceWith(newSelect);
              input = newSelect;
              // Optionally: you could restore options here if you want to keep previous values
              button.text("Stop");
              // Rebind the change event for child rendering
              newSelect.on("change", function () {
                const selected = $(this).val() || [];
                childContainer.empty();
                selected.forEach((value) => {
                  const childBlock = buildNode(value, []);
                  childContainer.append(childBlock);
                });
              });
            }
          });

          contentWrapper.append(title, input, childContainer);
          wrapper.append(contentWrapper, button);
          return wrapper;
        }

        // Save button click handler - CORRECT PLACE!
        $("#save-options").on("click", function () {
          const treeData = collectTreeData($("#selected-options"));
          console.log("treeData",treeData);
          const title = $("#tree-title").val();
          $.ajax({
            url: "save-options.php",
            method: "POST",
            data: {
              tree: JSON.stringify(treeData),
              tree: JSON.stringify(treeData),
              title: title,
            },
            success: function (res) {
              alert("Data saved successfully!");
              console.log(res);
            },
            error: function (xhr) {
              alert("Failed to save!\n" + xhr.responseText);
              console.error(xhr.responseText);
            },
          });
        });

        // Tree data collector - only once, not nested!
 function collectTreeData(container, parentOption = null) {
  const data = [];

  container.children(".d-flex").each(function () {
    const label = $(this).find("label").first().text().trim();
    const inputField = $(this).find("select, input").first();

    // If it's a text input, treat as a leaf/description node
    if (inputField.is("input")) {
      const description = inputField.val()?.trim();
      if (description) {
        data.push({
          description: description,
          parent: parentOption,
          children: []
        });
      }

    } else if (inputField.is("select")) {
      // If it's a select, add all selected options
      const selectedOptions = inputField.val() || [];
      selectedOptions.forEach((optionValue) => {
        // Find its direct child container
        const childContainer = $(this).find(".child-container").first();
        const children = collectTreeData(childContainer, optionValue);

        // Also push the children here (if needed in original format)
        // OR just pass parentOption down for the text inputs
      });
    }
  });

  // ✅ Group data by parent after collecting all
  const grouped = {};
  data.forEach(item => {
    if (!grouped[item.parent]) {
      grouped[item.parent] = {
        option: item.parent,
        children: []
      };
    }
    grouped[item.parent].children.push(item.description);
  });

  const parent = Object.values(grouped);
  console.log(parent);

  return parent;
}

        $("#tags").on("change", function () {
          renderTree();
        });

        renderTree(); // initial load
      });
    </script>
  </body>
</html>
