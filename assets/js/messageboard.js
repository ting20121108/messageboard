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
					style:　"width: 18rem;",
				}).append(
					$("<div/>", {
						class: "card-body",
					}).append(
						$("<span/>").text("暱稱："),
						$("<span/>", {
							class: 'name',
						}).text(message['name']),
						$("<span/>", {
							class: "card-text",
						}).text("  "),
						$("<small/>", {
							class: "text-muted",
						}).text(message['time']),
						$("<p/>").append(
							$("<span/>").text("內容："),
							$("<span/>", {
								class: 'content',
							}).text(message['content']),
						),
						$("<p>").append(
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
			alert(response['message']);
			$('.modal').hide();
			$.each(response['data'], function (key, message) {
				$("#show").prepend(
					$("<div/>", {
						id: message['id'],
						class: "card",
						style: "width: 18rem;",
					}).append(
						$("<div/>", {
							class: "card-body",
						}).append(
							$("<span/>").text("暱稱："),
							$("<span/>", {
								class: 'name',
							}).text(message['name']),
							$("<span/>", {
								class: "card-text",
							}).text("  "),
							$("<small/>", {
								class: "text-muted",
							}).text(message['time']),
							$("<p/>").append(
								$("<span/>").text("內容："),
								$("<span/>", {
									class: 'content',
								}).text(message['content']),
							),
							$("<p>").append(
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
				$("<button/>", {
					type: 'button',
					id: 'insert',
					class: 'btn btn-primary',
				}).text("送出"),
			);
		}
	});
});

$(document).on('click', '.delete', function () {
	let id = $(this).attr('data-id');
	$.ajax({
		url: '../api/Api/delete',
		type: 'POST',
		data: {
			'id': id,
		},
		success: function (response) {
			alert(response['message']);
			$("#" + id).remove();
		}
	});
});

$(document).on('click', '.edit', function () {
	var id = $(this).attr('data-id');
	var msg = $(this).closest('div'),
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
			alert(response['message']);
			$.each(response['data'], function (key, message) {
				$("#" + message['id']).empty();
				$("#" + message['id']).append(
					$("<div/>", {
						class: "card-body",
					}).append(
						$("<span/>").text("暱稱："),
						$("<span/>", {
							class: 'name',
						}).text(message['name']),
						$("<span/>").text(" 留言時間："),
						$("<span/>").text(message['time']),
						$("<p/>").append(
							$("<span/>").text("內容："),
							$("<span/>", {
								class: 'content',
							}).text(message['content']),
						),
						$("<p>").append(
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
						$("<span/>").text("暱稱："),
						$("<span/>", {
							class: 'name',
						}).text(message['name']),
						$("<span/>").text(" 留言時間："),
						$("<span/>").text(message['time']),
						$("<p/>").append(
							$("<span/>").text("內容："),
							$("<span/>", {
								class: 'content',
							}).text(message['content']),
						),
						$("<p>").append(
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
