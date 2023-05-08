<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Student List</h4>
            </div>
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12">

                    <div class="text-right">
                      <div class="selectgroup">
                        <label class="selectgroup-item">
                          <input type="radio" name="radio1" value="active" 
                          v-model="studentStatus" class="selectgroup-input-radio" title="Active students" @change="filterDatatable">
                          <span class="selectgroup-button"><b>Active ({{ activeStudent }})</b></span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="radio1" value="disable" 
                          v-model="studentStatus" class="selectgroup-input-radio" title="Disabled students" @change="filterDatatable">
                          <span class="selectgroup-button"><b>Disabled ({{ deactiveStudent }})</b></span>
                        </label>

                      </div>
                    </div>

                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                          <tr class="text-center">
                            <th>ID</th>
                            <th>Student name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>School</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Rating ({{ moment().format("MMMM") }}) </th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      moment: moment,
      activeStudent: 0,
      deactiveStudent: 0,
      studentStatus: "active",
      datatable: null,
    }
  },
  methods: {
    async getStudentCount() {
      await axios.get("/admin/api/get-student-count").then(resp=>{
        return resp.data;
      }).then(data=>{
        this.activeStudent = data.active_student;
        this.deactiveStudent = data.disable_student;
      }).catch(err=>{
        console.error(err.response.data);
      })
    },
    filterDatatable() {
      this.datatable.ajax.url("/admin/api/get-student-list?status="+this.studentStatus).load();
    },
  },
  async mounted() {
    await this.getStudentCount();
    var _self = this;

    this.datatable = $('#datatable').DataTable({
      processing: true,
      serverside:true,
      ajax: '/admin/api/get-student-list?status=active',
      lengthChange : true,
      columns: [
        {data: "id"},
        { data: "name" },
        { data: "phone" },
        { data: "address" },
        { data: "school" },
        { data: "class" },
        { data: "section" },
        { data: 'ratings' },
        {data: 'status'},
        { data: 'action', name: 'action', orderable: false, searchable: false  }
      ],
      dom: 'Bfrtip',
      buttons: [
          {
              extend: 'copyHtml5',
              exportOptions: {
                  columns: [ 0, ':visible' ]
              }
          },
          {
              extend: 'excelHtml5',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdfHtml5',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
            extend: 'colvis',
            text: '<i class="fas fa-eye"></i> Columns'
          }
      ],
      createdRow: function( row, data, dataIndex ) {
          // Set the data-status attribute, and add a class
          $( row ).addClass("text-center");
      }

    })
    $('#datatable').removeClass("dataTable");
    $('#datatable').css("width" , "100%");

    $(document).on("change","input[data-activate]",function(){
      let studentId = $(this).data("activate");
      axios.post("/admin/api/change-student-status",{
        studentId : studentId,
      }).then(resp=>{
        return resp.data;
      }).then(data=>{
        if(data.status == "ok") {
          if(data.change_status == 'active') {
            toastr.success(data.msg,"Activated");

            let statusText = $(`span[data-activate-text="${studentId}"]`);

            statusText.removeClass("text-muted");
            statusText.addClass("text-success");
            statusText.text("Active");
            _self.activeStudent++;
            _self.deactiveStudent--;
          }
          else if(data.change_status == 'disabled') {
            toastr.error(data.msg,"De-activated");

            let statusText = $(`span[data-activate-text="${studentId}"]`);

            statusText.removeClass("text-success");
            statusText.addClass("text-muted");
            statusText.text("Disabled");
            _self.activeStudent--;
            _self.deactiveStudent++;

          }
        }
      }).catch(err=>{
        toastr.error("Failed to update student","Failed");
        console.error(err.response.data);
      });
    });

    $(document).on("click","button[data-student-delete]",function(){
      let studentId = $(this).data("student-delete");
      swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post("/admin/api/delete-student",{
            studentId: studentId,
          }).then(resp=>{
            return resp.data;
          }).then(data=>{
            if(data.status == "ok")
            {
              swal.fire("Student deleted",data.msg,"success").then(()=>{
                window.location.reload();
              });
            }
          }).catch(err=>{
            toastr.error("Failed to delete","Internal Server error");
            console.error(err.response.data);
          })
        }
      })
    });

    $(document).on("click","button[data-student-edit]",function(){
      let studentId = $(this).data('student-edit');
      _self.$router.push({
        name: 'admin.edit-student',
        params: {
          studentId : studentId,
        }
      });
    });

    $(document).on("click","button[data-ratings]",function() {
      let studentId = $(this).data("ratings");
      _self.$router.push({
        name: 'admin.student-rating',
        params: {
          studentId: studentId
        }
      })
    });
  }
}
</script>

<style>

</style>