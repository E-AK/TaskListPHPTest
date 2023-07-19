<?php include("./templates/header.php"); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="text-center mb-4">Task List</h2>

            <!-- Форма для добавления задания -->
            <div class="task-controls mb-4">
                <form id="addTaskForm" method="POST" action="">
                    <div class="input-group">
                        <input type="text" name="task" id="taskInput" class="form-control" placeholder="Enter text..." required>
                        <button type="submit" name="addTask" class="btn btn-primary">ADD TASK</button>
                    </div>
                </form>
                <form id="taskControlsForm" method="POST" action="">
                    <div class="mt-3">
                        <button type="button" name="removeAll" id="removeAllBtn" class="btn btn-danger">Remove All</button>
                        <button type="button" name="readyAll" id="readyAllBtn" class="btn btn-success">Ready All</button>
                    </div>
                </form>
            </div>

            <!-- Список задач -->
            <div id="taskList"></div>
        </div>
    </div>
</div>

<?php include("./templates/footer.php"); ?>
