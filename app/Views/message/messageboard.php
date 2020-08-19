<div class="container">
	<div class="row">
		<div class="col-sm"></div>
		<div class="col-sm text-center">
			<h1>留言板</h1>
		</div>
		<div class="col-sm">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal" style="margin-top: 1%; margin-bottom: 1%;">
				新增
			</button>
		</div>
	</div>

	<div id="show" class="row"></div>
	<!-- Modal -->
	<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="createModalLabel">新增留言</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form class="createForm" method="POST">
					<table>
						<tr>
							<td>暱稱：</td>
							<td><input type="text" size="25" id="name"></td>
						</tr>
						<tr>
							<td>內容：</td>
							<td><textarea rows="10" id="content" wrap="soft"></textarea></td>
						</tr>
					</table>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="insert">送出</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		function getMessages() {
			return new Promise((resolve) => {
				$.ajax({
					url: '<?php echo base_url('Api/getMessage'); ?>',
					method: 'POST',
					success: (response) => {
						resolve(response);

					}
				})
			})
		}

		getMessages().then((messages) => {
			$.each(messages.reverse(), function(key, message) {
				$("#show").append(
					$("<div/>", {
						id: message['id'],
						class: "card",
						style: "width: 20rem; margin: 2%;",
					}).append(
						$("<div/>", {
							class: "card-body",
						}).append(
							$("<div/>", {
								class: "row justify-content-between",
								id: message['id'],
							}).append(
								$("<div/>", {
									class: "col",
								}).append(
									$("<span/>").text("暱稱："),
									$("<span/>", {
										class: 'name',
									}).text(message['name']),
								),
								$("<div/>", {
									class: "col"
								}).append(
									$("<span/>", {
										class: "card-text",
									}).text("  "),
									$("<small/>", {
										class: "text-muted",
									}).text(message['time']),
								),
							),
							$("<div/>").append(
								$("<span/>").text("內容："),
								$("<span/>", {
									class: 'content',
								}).text(message['content']),
							),
							$("<div>", {
								class: 'row justify-content-around',
							}).append(
								$("<button/>", {
									type: 'button',
									'data-id': message['id'],
									class: 'delete btn btn-danger',
								}).text("刪除"),
								$("<button/>", {
									type: 'button',
									'data-id': message['id'],
									class: 'edit btn btn-success',
								}).text("編輯"),
							),
						),
					),
				);
			});
		});

		$('#insert').on('click', function() {
			getCreateMessage();
		});
	});

	function getCreateMessage() {
		let name = $('#name').val();
		let content = $('#content').val();
		if (name != '' && content != '') {
			submitCreateMessage(name, content);
		} else {
			Swal.fire({
				title: '警告',
				text: "暱稱或內容不可空白",
				icon: 'warning',
				confirmButtonColor: 'red',
				confirmButtonText: '確定',
			})
		}
	}

	function submitCreateMessage(name, content) {
		$.ajax({
			url: '<?php echo base_url('Api/create'); ?>',
			type: 'POST',
			data: {
				'name': name,
				'content': content,
			},
			success: function(response) {
				Swal.fire({
					icon: 'success',
					title: response['message'],
				});
				$("#show").prepend(
					$("<div/>", {
						id: response['data']['id'],
						class: "card",
						style: "width: 20rem; margin: 2%;",
					}).append(
						$("<div/>", {
							class: "card-body",
						}).append(
							$("<div/>", {
								class: "row justify-content-between",
							}).append(
								$("<div/>", {
									class: "col"
								}).append(
									$("<span/>").text("暱稱："),
									$("<span/>", {
										class: 'name',
									}).text(response['data']['name']),
								),
								$("<div/>", {
									class: "col"
								}).append(
									$("<span/>", {
										class: "card-text",
									}).text("  "),
									$("<small/>", {
										class: "text-muted",
									}).text(response['data']['time']),
								),
							),
							$("<p/>").append(
								$("<span/>").text("內容："),
								$("<span/>", {
									class: 'content',
								}).text(response['data']['content']),
							),
							$("<div>", {
								class: 'row justify-content-around',
							}).append(
								$("<button/>", {
									type: 'button',
									'data-id': response['data']['id'],
									class: 'delete btn btn-danger',
								}).text("刪除"),
								$("<button/>", {
									type: 'button',
									'data-id': response['data']['id'],
									class: 'edit btn btn-success',
								}).text("編輯"),
							),
						),
					),
				);
				emptyCreateForm();
			},
		});
	}

	$(document).on('click', '.delete', function() {
		let id = $(this).attr('data-id');
		Swal.fire({
			title: '確定要刪除?',
			text: "刪除後資料無法復原!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: '確定',
			cancelButtonText: '取消',
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '<?php echo base_url('Api/delete'); ?>',
					type: 'POST',
					data: {
						'id': id,
					},
					success: function(response) {
						Swal.fire({
							icon: 'success',
							title: response['message'],
						});
						$("#" + id).remove();
					}
				});
			}
		})
	});

	$(document).on('click', '.edit', function() {
		var id = $(this).attr('data-id');
		var msg = $(this).closest('#' + id),
			name = msg.find('.name').text(),
			content = msg.find('.content').text();
		$("#" + id).html(
			$("<form/>", {
				action: '',
				method: 'POST',
			}).append(
				$("<span/>").text("暱稱："),
				$("<input/>", {
					type: 'text',
					id: 'newName',
					required: true,
					value: name,
				}),
				$("<p/>").append(
					$("<span/>").text("內容："),
					$("<textarea/>", {
						rows: '10',
						cols: '20',
						required: true,
						id: 'newContent',
					}).text(content),
				),
				$("<div>", {
					class: 'row justify-content-around',
				}).append(
					$("<button/>", {
						type: 'button',
						'data-id': id,
						class: 'update btn btn-success',
					}).text("修改"),
					$("<button/>", {
						type: 'button',
						'data-id': id,
						class: 'cancel btn btn-secondary',
					}).text("取消"),
				),
			),
		);
	});

	$(document).on('click', '.update', function() {
		let id = $(this).attr('data-id');
		let name = $('#newName').val();
		let content = $('#newContent').val();
		submitUpdateMessage(id, name, content);
	});

	function submitUpdateMessage(id, name, content) {
		$.ajax({
			url: '<?php echo base_url('Api/update'); ?>',
			type: 'POST',
			data: {
				'id': id,
				'name': name,
				'content': content,
			},
			success: function(response) {
				if (response['status'] === "success") {
					Swal.fire({
						icon: 'success',
						title: response['message'],
					});

					$("#" + response['data']['id']).empty();
					$("#" + response['data']['id']).append(
						$("<div/>", {
							class: "card-body",
						}).append(
							$("<div/>", {
								class: "row justify-content-between",
							}).append(
								$("<div/>", {
									class: "col"
								}).append(
									$("<span/>").text("暱稱："),
									$("<span/>", {
										class: 'name',
									}).text(response['data']['name']),
								),
								$("<div/>", {
									class: "col"
								}).append(
									$("<span/>", {
										class: "card-text",
									}).text("  "),
									$("<small/>", {
										class: "text-muted",
									}).text(response['data']['time']),
								),
							),
							$("<p/>").append(
								$("<span/>").text("內容："),
								$("<span/>", {
									class: 'content',
								}).text(response['data']['content']),
							),
							$("<div>", {
								class: 'row justify-content-around',
							}).append(
								$("<button/>", {
									type: 'button',
									'data-id': response['data']['id'],
									class: 'delete btn btn-danger',
								}).text("刪除"),
								$("<button/>", {
									type: 'button',
									'data-id': response['data']['id'],
									class: 'edit btn btn-success',
								}).text("編輯"),
							),
						),
					);
				} else {
					Swal.fire({
						icon: 'error',
						title: response['message'],
					});
				}
			}
		});
	}

	$(document).on('click', '.cancel', function() {
		let id = $(this).attr('data-id');

		$.ajax({
			url: '<?php echo base_url('Api/getOne'); ?>',
			type: 'POST',
			data: {
				'id': id,
			},
			success: function(response) {
				$("#" + response['id']).empty();
				$("#" + response['id']).append(
					$("<div/>", {
						class: "card-body",
					}).append(
						$("<div/>", {
							class: "row justify-content-between",
						}).append(
							$("<div/>", {
								class: "col"
							}).append(
								$("<span/>").text("暱稱："),
								$("<span/>", {
									class: 'name',
								}).text(response['name']),
							),
							$("<div/>", {
								class: "col"
							}).append(
								$("<span/>", {
									class: "card-text",
								}).text("  "),
								$("<small/>", {
									class: "text-muted",
								}).text(response['time']),
							),
						),
						$("<p/>").append(
							$("<span/>").text("內容："),
							$("<span/>", {
								class: 'content',
							}).text(response['content']),
						),
						$("<div>", {
							class: 'row justify-content-around',
						}).append(
							$("<button/>", {
								type: 'button',
								'data-id': response['id'],
								class: 'delete btn btn-danger',
							}).text("刪除"),
							$("<button/>", {
								type: 'button',
								'data-id': response['id'],
								class: 'edit btn btn-success',
							}).text("編輯"),
						),
					),
				);
			}
		});
	});

	function emptyCreateForm() {
		$('.createForm').empty().append(
			$("<div/>").append(
				$("<span>").text("暱稱："),
				$("<input>", {
					type: 'text',
					size: 25,
					id: 'name',
					required: true,
				}),
			),
			$("<div/>").append(
				$("<span>").text("內容："),
				$("<textarea>", {
					rows: '10',
					cols: '20',
					wrap: 'soft',
					required: true,
				}),
			),
			$("<div/>", {
				class: 'modal-footer',
			}).append(
				$("<button>", {
					type: 'button',
					class: 'btn btn-primary',
					'data-dismis': "modal",
					id: "insert"
				}).text("送出"),
				$("<button>", {
					type: 'button',
					class: 'btn btn-secondary',
					'data-dismis': "modal",
				}).text("取消"),
			),
		);
	}
</script>