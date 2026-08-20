import type {
  Profile,
  Skill,
  Experience,
  Project,
  EducationEntry,
  OrganizationEntry,
  PortfolioBootstrap,
} from "./types";

const API_URL = import.meta.env.VITE_API_URL ?? "http://localhost:8000/api";

async function get<T>(path: string): Promise<T> {
  const res = await fetch(`${API_URL}${path}`);
  if (!res.ok) {
    throw new Error(`Request to ${path} failed with status ${res.status}`);
  }
  const json = await res.json();
  return (json.data ?? json) as T;
}

export const api = {
  getBootstrap: (lang: string) =>
    get<PortfolioBootstrap>(`/bootstrap?lang=${lang}`),
  // Individual endpoints, kept for cases that need just one resource.
  getProfile: (lang: string) => get<Profile>(`/profile?lang=${lang}`),
  getSkills: () => get<Skill[]>("/skills"),
  getExperiences: (lang: string) =>
    get<Experience[]>(`/experiences?lang=${lang}`),
  getProjects: (lang: string) => get<Project[]>(`/projects?lang=${lang}`),
  getEducation: () => get<EducationEntry[]>("/education"),
  getOrganizations: (lang: string) =>
    get<OrganizationEntry[]>(`/organizations?lang=${lang}`),
  // Not fetched via JS — used directly as a download link's href.
  cvUrl: (lang: string) => `${API_URL}/cv?lang=${lang}`,
};
