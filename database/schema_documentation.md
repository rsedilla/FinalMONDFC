# FinalMONDFC Database Schema Documentation

## Overview
This document describes the normalized database schema for the FinalMONDFC church management system. It covers all major tables, relationships, and their purposes.

---

## Main Entities

### 1. church_attenders
- Stores information about all church attenders.
- Key columns: id, name, contact info, etc.

### 2. cell_groups
- Represents small groups within the church.
- Key columns: id, name, cell_group_type_id

### 3. cell_group_types
- Lookup table for types of cell groups.
- Key columns: id, name

### 4. cell_leaders
- Leaders assigned to cell groups.
- Key columns: id, church_attender_id, cell_group_id

### 5. cell_members
- Members assigned to cell groups.
- Key columns: id, church_attender_id, cell_group_id

### 6. g12_leaders
- G12 leaders overseeing cell leaders and members.
- Key columns: id, church_attender_id

### 7. network_leaders
- Network leaders overseeing G12 leaders and cell groups.
- Key columns: id, church_attender_id

---

## Relationships & Pivot Tables

- **cell_group_church_attender**: Connects attenders to cell groups.
- **g12_leader_cell_leader**: Connects G12 leaders to cell leaders.
- **g12_leader_cell_member**: Connects G12 leaders to cell members.
- **g12_leader_church_attender**: Connects G12 leaders to attenders.
- **network_leader_g12_leader**: Connects network leaders to G12 leaders.
- **network_leader_cell_leader**: Connects network leaders to cell leaders.
- **network_leader_cell_member**: Connects network leaders to cell members.
- **network_leader_church_attender**: Connects network leaders to attenders.
- **network_leader_emerging_leader**: Connects network leaders to emerging leaders.

---

## Attendance Tracking

### 1. cell_group_sessions
- Records each cell group meeting/session.

### 2. cell_group_session_attendance
- Tracks attendance of church attenders in cell group sessions.

### 3. sunday_service_sessions
- Records each Sunday service session.

### 4. sunday_service_session_attendance
- Tracks attendance of church attenders in Sunday service sessions.

---

## Training Progress

### 1. training_progress
- Tracks training progress for each attender.
- Key columns: id, church_attender_id, training_progress_type_id, status, date

### 2. training_progress_types
- Reference table for types/stages of training progress.
- Key columns: id, name

---

## Other Tables
- **users**: System users (for authentication).
- **migrations**: Laravel migration tracking.
- **failed_jobs, jobs, job_batches, password_reset_tokens**: Laravel system tables.

---

## Example Relationships
- A cell member belongs to a cell group, which is linked to a cell leader, G12 leader, and network leader via pivot tables.
- Attendance and training progress are tracked per attender, with reference tables for types and statuses.

---

## Notes
- All relationships use foreign keys for referential integrity.
- Reference tables (like training_progress_types, cell_group_types) ensure normalization and easy maintenance.
- Pivot tables allow flexible many-to-many relationships between leaders, members, and groups.

---

## ERD (Entity Relationship Diagram)
- [Not included: Can be generated using tools like dbdiagram.io or Laravel ERD packages.]

---

For further details, see individual migration files or request specific table structures.
