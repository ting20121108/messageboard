$(document).ready(function () {
	function getMessages() {
		return new Promise((resolve) => {
			$.ajax({
				url: '../api/Api/getMessage',
				method: 'POST',
				success: (response) => {
					resolve(response);
				}
			})
		})
	}

	getMessages().then((messages) => {
		$.each(messages.reverse(), function (key, message) {
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
});

$(document).on('click', '#insert', function () {
	let name = $('#name').val();
	let content = $('#content').val();
	$.ajax({
		url: '../api/Api/create',
		type: 'POST',
		data: {
			'name': name,
			'content': content,
		},
		success: function (response) {
			Swal.fire({
				icon: 'success',
				title: response['message'],
			});

			$('.modal').hide();

			$.each(response['data'], function (key, message) {
				$("#show").prepend(
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
							}).append(
								$("<div/>", {
									class: "col"
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
							$("<p/>").append(
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
			emptyCreateForm();
		}
	});
});

$(document).on('click', '.delete', function () {
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
				url: '../api/Api/delete',
				type: 'POST',
				data: {
					'id': id,
				},
				success: function (response) {
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

$(document).on('click', '.edit', function () {
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

$(document).on('click', '.update', function () {
	let id = $(this).attr('data-id');
	let name = $('#newName').val();
	let content = $('#newContent').val();
	$.ajax({
		url: '../api/Api/update',
		type: 'POST',
		data: {
			'id': id,
			'name': name,
			'content': content,
		},
		success: function (response) {
			Swal.fire({
				icon: 'success',
				title: response['message'],
			});

			$.each(response['data'], function (key, message) {
				$("#" + message['id']).empty();
				$("#" + message['id']).append(
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
						$("<p/>").append(
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
				);
			})
		}
	});
});

$(document).on('click', '.cancel', function () {
	let id = $(this).attr('data-id');

	$.ajax({
		url: '../api/Api/getOne',
		type: 'POST',
		data: {
			'id': id,
		},
		success: function (response) {
			$.each(response, function (key, message) {
				$("#" + message['id']).empty();
				$("#" + message['id']).append(
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
						$("<p/>").append(
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
				);
			})
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
