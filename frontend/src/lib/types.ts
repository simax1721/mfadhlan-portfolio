export interface Profile {
  name: string;
  role: string;
  tagline: string | null;
  summary: string;
  email: string;
  phone: string | null;
  location: string;
  github_url: string | null;
  linkedin_url: string | null;
  cv_url: string | null;
  photo_url: string | null;
}

export interface Skill {
  id: number;
  name: string;
  category: "Backend" | "Frontend" | "Database" | "Tools" | string;
}

export interface Experience {
  id: number;
  title: string;
  company: string;
  period: string;
  tech_stack: string[];
  bullets: string[];
}

export interface Project {
  id: number;
  title: string;
  subtitle: string | null;
  description: string | null;
  tech_stack: string[];
  bullets: string[];
  image_url: string | null;
  demo_url: string | null;
  github_url: string | null;
  featured: boolean;
}

export interface EducationEntry {
  id: number;
  degree: string;
  institution: string;
  period: string;
}

export interface OrganizationEntry {
  id: number;
  role: string;
  organization: string;
  year: string;
  description: string | null;
}

/** Shape of the combined /api/bootstrap payload. */
export interface PortfolioBootstrap {
  profile: Profile;
  skills: Skill[];
  experiences: Experience[];
  projects: Project[];
  education: EducationEntry[];
  organizations: OrganizationEntry[];
}
