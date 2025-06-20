<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated users cannot access students', function () {
    $response = $this->get('/students');
    $response->assertRedirect('/login');
});

test('authenticated users can view students index', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/students');
    
    $response->assertStatus(200);
    $response->assertViewIs('students.index');
});

test('authenticated users can view create student form', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/students/create');
    
    $response->assertStatus(200);
    $response->assertViewIs('students.create');
});

test('can create a new student with valid data', function () {
    $user = User::factory()->create();
    
    $studentData = [
        'student_id' => 'STU001',
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'course' => 'Computer Science',
        'year_level' => 2,
    ];
    
    $response = $this->actingAs($user)->post('/students', $studentData);
    
    $response->assertRedirect('/students');
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('students', $studentData);
});

test('cannot create student with duplicate student_id', function () {
    $user = User::factory()->create();
    
    // Create first student
    Student::factory()->create(['student_id' => 'STU001']);
    
    $studentData = [
        'student_id' => 'STU001', // Duplicate ID
        'name' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
        'course' => 'Mathematics',
        'year_level' => 1,
    ];
    
    $response = $this->actingAs($user)->post('/students', $studentData);
    
    $response->assertSessionHasErrors('student_id');
    $this->assertDatabaseMissing('students', [
        'name' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
    ]);
});

test('cannot create student with duplicate email', function () {
    $user = User::factory()->create();
    
    // Create first student
    Student::factory()->create(['email' => 'test@example.com']);
    
    $studentData = [
        'student_id' => 'STU002',
        'name' => 'Jane Doe',
        'email' => 'test@example.com', // Duplicate email
        'course' => 'Mathematics',
        'year_level' => 1,
    ];
    
    $response = $this->actingAs($user)->post('/students', $studentData);
    
    $response->assertSessionHasErrors('email');
});

test('can view individual student', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create();
    
    $response = $this->actingAs($user)->get("/students/{$student->id}");
    
    $response->assertStatus(200);
    $response->assertViewIs('students.show');
    $response->assertViewHas('student', $student);
});

test('can view edit student form', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create();
    
    $response = $this->actingAs($user)->get("/students/{$student->id}/edit");
    
    $response->assertStatus(200);
    $response->assertViewIs('students.edit');
    $response->assertViewHas('student', $student);
});

test('can update student with valid data', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create();
    
    $updateData = [
        'student_id' => 'STU003',
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'course' => 'Updated Course',
        'year_level' => 3,
    ];
    
    $response = $this->actingAs($user)->put("/students/{$student->id}", $updateData);
    
    $response->assertRedirect('/students');
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('students', $updateData);
});

test('can delete student', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create();
    
    $response = $this->actingAs($user)->delete("/students/{$student->id}");
    
    $response->assertRedirect('/students');
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('students', ['id' => $student->id]);
});

test('validation requires all fields', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/students', []);
    
    $response->assertSessionHasErrors(['student_id', 'name', 'email', 'course', 'year_level']);
});

test('validation requires valid email format', function () {
    $user = User::factory()->create();
    
    $studentData = [
        'student_id' => 'STU001',
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'course' => 'Computer Science',
        'year_level' => 2,
    ];
    
    $response = $this->actingAs($user)->post('/students', $studentData);
    
    $response->assertSessionHasErrors('email');
});

test('validation requires year_level to be integer between 1 and 10', function () {
    $user = User::factory()->create();
    
    $studentData = [
        'student_id' => 'STU001',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'course' => 'Computer Science',
        'year_level' => 15, // Invalid year level
    ];
    
    $response = $this->actingAs($user)->post('/students', $studentData);
    
    $response->assertSessionHasErrors('year_level');
});
